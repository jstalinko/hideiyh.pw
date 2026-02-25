<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitorLogResource\Pages;
use App\Filament\Resources\VisitorLogResource\RelationManagers;
use App\Models\VisitorLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitorLogResource extends Resource
{
    protected static ?string $model = VisitorLog::class;

    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';

    protected static ?string $navigationGroup = 'Integration';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }
    public static function canView(Model $record): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
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
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flow.name')->label('Flow Name'),
                Tables\Columns\TextColumn::make('reason')->label('Reason'),
                Tables\Columns\TextColumn::make('ip')->label('IP Address'),
                Tables\Columns\TextColumn::make('country')->label('Country'),
                Tables\Columns\TextColumn::make('device')->label('Device'),
                Tables\Columns\TextColumn::make('browser')->label('Browser'),
                Tables\Columns\TextColumn::make('referer')->label('Referer'),
                Tables\Columns\TextColumn::make('user_agent')->label('User Agent')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('isp')->label('ISP'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At'),
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
            'index' => Pages\ListVisitorLogs::route('/'),
            'create' => Pages\CreateVisitorLog::route('/create'),
            'view' => Pages\ViewVisitorLog::route('/{record}'),
            'edit' => Pages\EditVisitorLog::route('/{record}/edit'),
        ];
    }
}
