<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateFlow extends CreateRecord
{
    protected static string $resource = FlowResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['user_id'] = Auth::user()->id;
        $data['uniqid'] = Str::uuid()->toString();

        return $data;
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('success', ['record' => $this->getRecord()]);
    }
}
