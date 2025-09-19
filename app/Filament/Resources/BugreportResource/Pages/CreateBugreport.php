<?php

namespace App\Filament\Resources\BugreportResource\Pages;

use App\Filament\Resources\BugreportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBugreport extends CreateRecord
{
    protected static string $resource = BugreportResource::class;

    protected  static bool $canCreateAnother = false;

   
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['active'] = false;
        $data['status'] = 'pending';
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
    protected function getCreatedNotificationTitle(): ?string
{
    return 'Thanks for your report! Bug submitted';
}
}
