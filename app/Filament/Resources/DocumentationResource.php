<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Documentation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DocumentationResource\Pages;
use App\Filament\Resources\DocumentationResource\RelationManagers;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class DocumentationResource extends Resource
{
    protected static ?string $model = Documentation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Administrator';

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('super_admin') ? true : false;
    }
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
          
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Documentation::class, 'slug', ignoreRecord: true),                
            TinyEditor::make('content')
    ->fileAttachmentsDisk('public')
    ->fileAttachmentsVisibility('public')
    ->fileAttachmentsDirectory('uploads')
    ->profile('default|simple|full|minimal|none|custom')
    ->columnSpan('full')
    ->required(),
    Forms\Components\TextInput::make('version')->label('Contability Version'),
    Forms\Components\Select::make('order')->options(range(1,100))->label('Sort Menu'),
    Forms\Components\Toggle::make('is_published'),

            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            Tables\Columns\ToggleColumn::make('is_published'), 
            Tables\Columns\TextColumn::make('version'),
            Tables\Columns\TextColumn::make('order')->label('Sort'),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('category')
                ->options([
                    'getting-started' => 'Getting Started',
                    'installation' => 'Installation',
                    'configuration' => 'Configuration',
                    'advanced' => 'Advanced',
                    'api' => 'API Reference',
                ]),
            
            Tables\Filters\TernaryFilter::make('is_published'),
            
            Tables\Filters\Filter::make('published_at')
                ->form([
                    Forms\Components\DatePicker::make('published_from'),
                    Forms\Components\DatePicker::make('published_until'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when(
                            $data['published_from'],
                            fn($query) => $query->whereDate('published_at', '>=', $data['published_from']),
                        )
                        ->when(
                            $data['published_until'],
                            fn($query) => $query->whereDate('published_at', '<=', $data['published_until']),
                        );
                })
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDocumentations::route('/'),
            'create' => Pages\CreateDocumentation::route('/create'),
            'view' => Pages\ViewDocumentation::route('/{record}'),
            'edit' => Pages\EditDocumentation::route('/{record}/edit'),
        ];
    }
}
