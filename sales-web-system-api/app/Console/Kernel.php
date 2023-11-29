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
        $scheduleTime = env('SCHEDULE_TIME', '23:59');

        $schedule->command('sales:send-daily-report-admin')->dailyAt($scheduleTime);
        $schedule->command('sales:send-daily-report-sellers')->dailyAt($scheduleTime);
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
