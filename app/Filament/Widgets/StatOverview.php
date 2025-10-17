<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $domainQuotaUser = auth()->user()->domain_quota;
        $domainQuotaUsed = \App\Models\Domain::where('user_id', auth()->id())->count();
        $domainQuotaRemaining = $domainQuotaUser - $domainQuotaUsed;
        return [
            Stat::make('Members', \App\Models\User::count())
                ->color('primary')
                ->icon('heroicon-o-users'),
            Stat::make('Your Domains Quota', $domainQuotaUsed.'/'.$domainQuotaUser)
                ->color('success')
                ->icon('heroicon-o-globe-alt')
                ->description('Remaining: '.$domainQuotaRemaining. ' | Registered: '.$domainQuotaUsed.' | Quota: '.$domainQuotaUser)
                ->url(url('/dashboard/domains')),
            Stat::make('Traffic Hits', \App\Models\Domain::count('traffic_count'))
                ->color('warning')
                ->icon('heroicon-o-chart-bar-square')
                ->description('Total traffic all domains')
                ->url(url('/dashboard/downloads')),


        ];
    }
}
