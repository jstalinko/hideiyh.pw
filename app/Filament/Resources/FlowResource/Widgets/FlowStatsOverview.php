<?php

namespace App\Filament\Resources\FlowResource\Widgets;

use App\Models\VisitorLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class FlowStatsOverview extends BaseWidget
{
    public ?string $record = null;

    protected function getStats(): array
    {
        $flowId = $this->record;

        $totalTraffic = VisitorLog::where('flow_id', $flowId)->count();
        $todayTraffic = VisitorLog::where('flow_id', $flowId)->whereDate('created_at', Carbon::today())->count();
        $allowedTraffic = VisitorLog::where('flow_id', $flowId)->where('reason', 'passed')->count();
        $blockedTraffic = VisitorLog::where('flow_id', $flowId)->where('reason', '!=', 'passed')->count();

        return [
            Stat::make('Today Traffic', number_format($todayTraffic))
                ->description('Traffic arrived today')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Traffic', number_format($totalTraffic))
                ->description('All time traffic')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Allowed Traffic', number_format($allowedTraffic))
                ->description('Traffic successfully passed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Blocked Traffic', number_format($blockedTraffic))
                ->description('Traffic blocked by rules')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
