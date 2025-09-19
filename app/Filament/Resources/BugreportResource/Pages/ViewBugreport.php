<?php

namespace App\Filament\Resources\BugreportResource\Pages;

use App\Filament\Resources\BugreportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBugreport extends ViewRecord
{
    protected static string $resource = BugreportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
