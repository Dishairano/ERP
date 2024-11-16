<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\InitializeLeaveBalances::class,
        Commands\CarryForwardLeaveBalances::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Initialize leave balances for new year on January 1st
        $schedule->command('leave:init-balances')
            ->yearly()
            ->on('January 1')
            ->at('00:01')
            ->description('Initialize leave balances for new year')
            ->emailOutputOnFailure(config('mail.admin_email'));

        // Carry forward leave balances on December 31st
        $schedule->command('leave:carry-forward')
            ->yearly()
            ->on('December 31')
            ->at('23:59')
            ->description('Carry forward leave balances to next year')
            ->emailOutputOnFailure(config('mail.admin_email'));

        // Daily check for leave balance updates
        $schedule->command('leave:init-balances')
            ->dailyAt('00:01')
            ->description('Initialize leave balances for new employees')
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     */
    protected function scheduleTimezone(): string
    {
        return config('app.timezone', 'UTC');
    }
}
