<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DomainResource\Pages;
use App\Filament\Resources\DomainResource\RelationManagers;
use App\Models\Domain;
use App\Models\DomainQuota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DomainResource extends Resource
{
    protected static ?string $model = Domain::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?int $navigationSort = 2;
   
    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // Custom filtering conditions
            ->when(
                auth()->user()->hasRole('super_admin'), 
                fn($query) => $query, // No restrictions for admins
                fn($query) => $query->where('user_id', auth()->id()) // Scope for non-admin users
            )
            // Additional custom filters
            ->orderByDesc('created_at');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('signature')
                    ->required(),
                Forms\Components\TextInput::make('domain')
                    ->required(),
                Forms\Components\TextInput::make('ip_server')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label('Email Registered'),
                Tables\Columns\TextColumn::make('signature')
                    ->searchable()->badge(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable()->badge(),
                Tables\Columns\TextColumn::make('connection_type')
                    ->searchable()->badge(),
                Tables\Columns\TextColumn::make('traffic_count')->label('Hits'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->headerActions([
                Tables\Actions\Action::make('add_domain_quota')->icon('heroicon-s-plus')
                ->form([
                    Forms\Components\TextInput::make('amount')->helperText('Banyaknya order tambah quota domain')
                    ->numeric()
                    ->minLength(1)
                    ->reactive()
                    ->afterStateUpdated(function($set,$state){
                        if($state < 1)
                        {
                            $total = str_replace(",",".",number_format(75000*1));
                            $set('amount',1);
                            $set('total_price' , $total);
                        }else{
                        $total = number_format(75000*$state);
                       $set('total_price' , str_replace(",",".",$total));
                        }
                    }),
                    Forms\Components\TextInput::make('total_price')->readonly(),
                ])->action(function($data){
                    $price = 75000;
                    $kodeUnik = rand(100,900);
                    $total = ($data['amount']*$price)+$kodeUnik;
                    $amount = $data['amount'];
                    $invoice = 'REQD'.date('dmYHi').auth()->user()->id;
                    $domainQuota = new DomainQuota();
                    $domainQuota->user_id = auth()->user()->id;
                    $domainQuota->amount =  $amount;
                    $domainQuota->invoice = $invoice;
                    $domainQuota->current_domain_quota = auth()->user()->domain_quota;
                    $domainQuota->total_price = $total;
                    $domainQuota->kode_unik = $kodeUnik;
                    $domainQuota->status = 'pending';
                    $domainQuota->save();
                    Notification::make('success')->title('Success !')->body('success request domain quota')->send();

                    return redirect('/invoice/'.$invoice);

                })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDomains::route('/'),
            'create' => Pages\CreateDomain::route('/create'),
            'view' => Pages\ViewDomain::route('/{record}'),
            'edit' => Pages\EditDomain::route('/{record}/edit'),
        ];
    }
}
