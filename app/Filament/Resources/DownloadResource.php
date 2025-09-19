<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DownloadResource\Pages;
use App\Filament\Resources\DownloadResource\RelationManagers;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DownloadResource extends Resource
{
    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-s-cloud-arrow-down';
    protected ?int $sort =1;


    public static function canDelete(Model $record): bool {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function canCreate(): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;

    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('version')
                    ->required(),
                Forms\Components\TextInput::make('tag_name')
                    ->required(),
                Forms\Components\RichEditor::make('changelog')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('file_url')
                    ->required(),
                Forms\Components\TextInput::make('product_url')
                    ->required(),
            ]);
    }
 
    public static function table(Table $table): Table
    {
        return $table
      
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->url(fn($record) => $record->product_url)->openUrlInNewTab(0),
                Tables\Columns\TextColumn::make('version')
                    ->searchable()->badge()->color('success'),
                Tables\Columns\TextColumn::make('tag_name')
                    ->searchable()->badge()->color('warning'),
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
                Tables\Actions\Action::make('download')->icon('heroicon-s-cloud-arrow-down')->color('info')->url(fn ($record) => $record->file_url) // Ambil URL dari kolom "file_url" di rekaman
                ->openUrlInNewTab(), // Membuka URL di tab baru
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('id','desc');
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
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'view' => Pages\ViewDownload::route('/{record}'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
