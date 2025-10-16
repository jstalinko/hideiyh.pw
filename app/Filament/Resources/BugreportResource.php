<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BugreportResource\Pages;
use App\Filament\Resources\BugreportResource\RelationManagers;
use App\Models\Bugreport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BugreportResource extends Resource
{
    protected static ?string $model = Bugreport::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string $label = 'Bug Report';
    protected static ?int $navigationSort = 4;

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
        $schema = [
            Forms\Components\TextInput::make('title')
                ->required()
                ->columnSpanFull(),
            Forms\Components\RichEditor::make('content')
                ->required()
                ->label('Describe bug(s)')
                ->columnSpanFull(),
            Forms\Components\FileUpload::make('image')
                ->image()
                ->label('Additional Image')
                ->columnSpanFull(),
        ];
    
        // Tambahkan field jika user memiliki role 'super_admin'
        if (auth()->user()->hasRole('super_admin')) {
            $schema[] = Forms\Components\Toggle::make('active')
                ->label('Active');
    
            $schema[] = Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'fix' => 'Fix',
                    'process' => 'Process',
                    'rejected' => 'Rejected',
                    'pending' => 'Pending',
                ])
                ->required();
        }
    
        return $form->schema($schema);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
             
                    Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fix' => 'success',     // Hijau
                        'process' => 'warning', // Kuning
                        'rejected' => 'danger', // Merah
                        'pending' => 'gray',    // Abu-abu
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBugreports::route('/'),
            'create' => Pages\CreateBugreport::route('/create'),
            'view' => Pages\ViewBugreport::route('/{record}'),
            'edit' => Pages\EditBugreport::route('/{record}/edit'),
        ];
    }
}
