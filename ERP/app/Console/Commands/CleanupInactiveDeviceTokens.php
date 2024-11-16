<?php

namespace App\Console\Commands;

use App\Models\PushNotificationSetting;
use App\Services\PushNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupInactiveDeviceTokens extends Command
{
  protected $signature = 'notifications:cleanup-tokens {--days=30 : Number of days of inactivity before cleanup}';
  protected $description = 'Clean up inactive device tokens and validate existing ones';

  public function handle()
  {
    $this->info('Starting device token cleanup...');

    try {
      // Delete tokens that have been inactive for the specified number of days
      $days = $this->option('days');
      $inactiveDate = now()->subDays($days);

      $inactiveTokens = PushNotificationSetting::where('updated_at', '<', $inactiveDate)
        ->where('is_active', false)
        ->delete();

      $this->info("Deleted {$inactiveTokens} inactive tokens older than {$days} days");

      // Validate remaining active tokens
      $activeSettings = PushNotificationSetting::where('is_active', true)->get();
      $invalidCount = 0;

      /** @var PushNotificationService */
      $notificationService = app(PushNotificationService::class);

      foreach ($activeSettings as $setting) {
        // Try to send a silent notification to validate token
        $result = $notificationService->sendToUsers(
          [$setting->user_id],
          'Token Validation',
          'Validating device token',
          ['type' => 'validation']
        );

        if (!$result) {
          $setting->update(['is_active' => false]);
          $invalidCount++;

          Log::info('Deactivated invalid device token', [
            'user_id' => $setting->user_id,
            'device_token' => $setting->device_token,
            'platform' => $setting->platform
          ]);
        }
      }

      $this->info("Deactivated {$invalidCount} invalid tokens");
      $this->info('Token cleanup completed successfully');

      return Command::SUCCESS;
    } catch (\Exception $e) {
      $this->error('Error during token cleanup: ' . $e->getMessage());
      Log::error('Token cleanup failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return Command::FAILURE;
    }
  }
}
