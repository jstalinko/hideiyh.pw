<?php

namespace App\Filament\Resources\BugreportResource\Pages;

use App\Filament\Resources\BugreportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBugreport extends EditRecord
{
    protected static string $resource = BugreportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
