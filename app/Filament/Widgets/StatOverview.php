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
            Stat::make('Plugin Current Version', \App\Models\Download::orderBy('id', 'desc')->first()?->version)
                ->color('warning')
                ->icon('heroicon-o-cog')
                ->description('Latest version available')
                ->url(url('/dashboard/downloads')),


        ];
    }
}
