<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('traffic:sync')->everyMinute();
         $schedule->command('subscriptions:notify-ending-soon')->dailyAt('12:00')->withoutOverlapping();
         $schedule->command('subscriptions:expire')->dailyAt('00:01')->withoutOverlapping();
         $schedule->command('notifications:send-whatsapp')->dailyAt('13:00')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__. '/../JustOrange/Cli');
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    
}
