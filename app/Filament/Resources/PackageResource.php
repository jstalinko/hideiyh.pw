<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'heroicon-m-queue-list';

    protected static ?string $navigationGroup = 'Administrator';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('billing_cycle')->options(['month' => 'Monthly' , 'year' => 'Yearly'])->default('month'),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Section::make('Feature')->schema([
                Forms\Components\Toggle::make('feature_acs_cloaking_script')
                    ->required(),
                Forms\Components\Toggle::make('feature_api_geolocation')
                    ->required(),
                Forms\Components\Toggle::make('feature_api_blocker')
                    ->required(),
                Forms\Components\TextInput::make('domain_quota')
                    ->required()
                    ->numeric()
                    ->default(5),
                Forms\Components\TextInput::make('visitor_quota_perday')
                    ->required()
                    ->numeric()
                    ->default(1000),
        Forms\Components\TextInput::make('price')
                ])->columns(3),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('feature_acs_cloaking_script')->toggleable(isToggledHiddenByDefault:true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('feature_api_geolocation')->toggleable(isToggledHiddenByDefault:true)
                    ->boolean(),
                Tables\Columns\IconColumn::make('feature_api_blocker')->toggleable(isToggledHiddenByDefault:true)
                    ->boolean(),
                Tables\Columns\TextColumn::make('price')->money(currency:'IDR'),
                Tables\Columns\TextColumn::make('domain_quota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visitor_quota_perday')
                    ->sortable()
                    ->formatStateUsing(function($state)  {
                        if($state == '-1') return 'Unlimited';
                        return number_format($state);
                    }),
                Tables\Columns\ToggleColumn::make('active')
                    ,
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
