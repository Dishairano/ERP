<?php

namespace App\Traits;

use App\Services\PushNotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait NotifiesOnDatabaseChanges
{
  protected static function bootNotifiesOnDatabaseChanges()
  {
    static::created(function ($model) {
      static::sendNotification($model, 'created');
    });

    static::updated(function ($model) {
      static::sendNotification($model, 'updated');
    });

    static::deleted(function ($model) {
      static::sendNotification($model, 'deleted');
    });
  }

  protected static function sendNotification($model, string $action)
  {
    try {
      $notificationService = app(PushNotificationService::class);
      $modelName = class_basename($model);
      $readableName = Str::title(Str::snake($modelName, ' '));

      // Get notification settings from the model if available
      $notificationConfig = method_exists($model, 'getNotificationConfig')
        ? $model->getNotificationConfig($action)
        : null;

      if ($notificationConfig === false) {
        return; // Skip notification if explicitly disabled
      }

      $title = $notificationConfig['title'] ?? "New $readableName {$action}";
      $body = $notificationConfig['body'] ?? static::generateNotificationBody($model, $action);
      $data = $notificationConfig['data'] ?? [
        'model_type' => $modelName,
        'model_id' => $model->id,
        'action' => $action
      ];

      // Get users to notify
      $userIds = static::getUsersToNotify($model, $action);
      if (!empty($userIds)) {
        $notificationService->sendToUsers($userIds, $title, $body, $data);
      }
    } catch (\Exception $e) {
      Log::error('Failed to send database change notification', [
        'model' => get_class($model),
        'action' => $action,
        'error' => $e->getMessage()
      ]);
    }
  }

  protected static function generateNotificationBody($model, string $action): string
  {
    $modelName = class_basename($model);
    $readableName = Str::title(Str::snake($modelName, ' '));
    $identifier = static::getModelIdentifier($model);

    switch ($action) {
      case 'created':
        return "New $readableName '$identifier' has been created";
      case 'updated':
        return "$readableName '$identifier' has been updated";
      case 'deleted':
        return "$readableName '$identifier' has been deleted";
      default:
        return "A change has occurred to $readableName '$identifier'";
    }
  }

  protected static function getModelIdentifier($model): string
  {
    // Try common identifier attributes
    $identifiers = ['name', 'title', 'subject', 'reference', 'number'];

    foreach ($identifiers as $identifier) {
      if (isset($model->$identifier)) {
        return $model->$identifier;
      }
    }

    // Fallback to ID
    return "#" . $model->id;
  }

  protected static function getUsersToNotify($model, string $action): array
  {
    // Default implementation - can be overridden in models
    if (method_exists($model, 'getNotificationRecipients')) {
      return $model->getNotificationRecipients($action);
    }

    // If model belongs to a user, notify that user
    if (isset($model->user_id)) {
      return [$model->user_id];
    }

    // If model has an owner or creator
    if (isset($model->owner_id)) {
      return [$model->owner_id];
    }

    if (isset($model->created_by)) {
      return [$model->created_by];
    }

    // Get all active users with notification settings
    return \App\Models\PushNotificationSetting::active()
      ->pluck('user_id')
      ->unique()
      ->toArray();
  }
}
