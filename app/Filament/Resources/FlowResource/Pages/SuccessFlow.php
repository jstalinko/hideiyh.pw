<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use App\Models\Flow;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class SuccessFlow extends Page
{
    use InteractsWithRecord;

    protected static string $resource = FlowResource::class;

    protected static string $view = 'filament.resources.flow-resource.pages.success-flow';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url('#'),
            Actions\Action::make('flowList')
                ->label('Flow List')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(fn() => FlowResource::getUrl('index')),
        ];
    }
}
