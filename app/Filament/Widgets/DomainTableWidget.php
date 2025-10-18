<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Domain;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Number;

class DomainTableWidget extends BaseWidget
{

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = null;


protected function setHeading(): void
{
    $domainUsed = Domain::where('user_id',auth()->user()->id)->count();
    static::$heading = 'Registered : '.$domainUsed.' Domain |  Quota: ' . auth()->user()->domain_quota. ' Domain';
}

public function mount(): void
{
    $this->setHeading();
}
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Domain::query()->where('user_id',auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label('Email Registered'),
                Tables\Columns\TextColumn::make('signature'),
                Tables\Columns\TextColumn::make('domain')
                ->searchable()->badge(),
            Tables\Columns\TextColumn::make('connection_type')->label('Connection Type')->badge(),
            Tables\Columns\TextColumn::make('traffic_count')->label('Traffic Today')->formatStateUsing(fn($state) => Number::forHumans($state))
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
              
            ])->headerActions([
               // Action::make('join')->label('JOIN TELEGRAM GROUP MEMBER')->action(fn() => redirect()->away('https://t.me/+a6MGYNGFGeVkYWI1')),
               // Action::make('docs')->label('DOCUMENTATION READ HERE')->action(fn() => redirect()->away('/docs'))->color('success'),
            ]);
    }
}
