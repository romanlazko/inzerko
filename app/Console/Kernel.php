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

        $schedule->command('queue:work --stop-when-empty --tries=2 --max-time=60')->everyMinute();
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run --only-db')->daily()->at('01:30');
        $schedule->command('model:prune')->daily()->at('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
