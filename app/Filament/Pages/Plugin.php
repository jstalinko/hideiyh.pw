<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Plugin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Integration';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.plugin';
}