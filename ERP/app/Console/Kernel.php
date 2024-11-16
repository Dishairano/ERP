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
    Commands\CleanupInactiveDeviceTokens::class,
  ];

  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule): void
  {
    // Clean up inactive device tokens daily at midnight
    $schedule->command('notifications:cleanup-tokens')
      ->daily()
      ->at('00:00')
      ->withoutOverlapping()
      ->runInBackground()
      ->emailOutputOnFailure(config('mail.admin_address'));

    // Add other scheduled tasks here
  }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
