<?php

namespace App\Filament\Resources\BugreportResource\Pages;

use App\Filament\Resources\BugreportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBugreports extends ListRecords
{
    protected static string $resource = BugreportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
