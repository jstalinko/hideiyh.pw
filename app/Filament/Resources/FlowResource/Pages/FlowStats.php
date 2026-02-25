<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use App\Models\VisitorLog;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class FlowStats extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = FlowResource::class;

    protected static string $view = 'filament.resources.flow-resource.pages.flow-stats';

    public $record;

    public function mount($record): void
    {
        $this->record = $record;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FlowResource\Widgets\FlowStatsOverview::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    public function getWidgetData(): array
    {
        return [
            'record' => $this->record,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(VisitorLog::query()->where('flow_id', $this->record)->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('ip')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('device'),
                TextColumn::make('browser'),
                TextColumn::make('referer')
                    ->limit(30)
                    ->tooltip(function (TextColumn $column): ?string {
                        return $column->getRecord()->referer;
                    }),
                TextColumn::make('reason')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'passed' => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ]);
    }
}
