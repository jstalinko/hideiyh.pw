<?php

namespace App\Filament\Resources\VisitorLogResource\Pages;

use App\Filament\Resources\VisitorLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVisitorLog extends ViewRecord
{
    protected static string $resource = VisitorLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
