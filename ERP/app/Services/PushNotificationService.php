<?php

namespace App\Services;

use App\Models\PushNotificationSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
  protected string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
  protected ?string $serverKey;

  public function __construct()
  {
    $this->serverKey = config('services.fcm.server_key');
  }

  /**
   * Send notification to a specific user
   */
  public function sendToUser(int $userId, string $title, string $body, array $data = []): bool
  {
    $deviceTokens = PushNotificationSetting::where('user_id', $userId)
      ->active()
      ->pluck('device_token')
      ->toArray();

    if (empty($deviceTokens)) {
      return false;
    }

    return $this->send($deviceTokens, $title, $body, $data);
  }

  /**
   * Send notification to multiple users
   */
  public function sendToUsers(array $userIds, string $title, string $body, array $data = []): bool
  {
    $deviceTokens = PushNotificationSetting::whereIn('user_id', $userIds)
      ->active()
      ->pluck('device_token')
      ->toArray();

    if (empty($deviceTokens)) {
      return false;
    }

    return $this->send($deviceTokens, $title, $body, $data);
  }

  /**
   * Send notification to specific platform users
   */
  public function sendToPlatform(string $platform, string $title, string $body, array $data = []): bool
  {
    $deviceTokens = PushNotificationSetting::forPlatform($platform)
      ->active()
      ->pluck('device_token')
      ->toArray();

    if (empty($deviceTokens)) {
      return false;
    }

    return $this->send($deviceTokens, $title, $body, $data);
  }

  /**
   * Send notification to all active devices
   */
  protected function send(array $deviceTokens, string $title, string $body, array $data = []): bool
  {
    if (!$this->serverKey) {
      Log::error('FCM server key not configured');
      return false;
    }

    try {
      $payload = [
        'registration_ids' => $deviceTokens,
        'notification' => [
          'title' => $title,
          'body' => $body,
          'sound' => 'default',
          'badge' => 1
        ],
        'data' => array_merge($data, [
          'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ]),
        'priority' => 'high'
      ];

      $response = Http::withHeaders([
        'Authorization' => 'key=' . $this->serverKey,
        'Content-Type' => 'application/json'
      ])->post($this->fcmUrl, $payload);

      if ($response->successful()) {
        $result = $response->json();
        Log::info('FCM Notification sent successfully', [
          'success' => $result['success'] ?? 0,
          'failure' => $result['failure'] ?? 0
        ]);
        return true;
      }

      Log::error('FCM Notification failed', [
        'status' => $response->status(),
        'body' => $response->body()
      ]);
      return false;
    } catch (\Exception $e) {
      Log::error('FCM Notification error', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return false;
    }
  }
}
