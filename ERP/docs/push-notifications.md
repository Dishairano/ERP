# Push Notifications Setup Guide

This guide explains how to set up and use push notifications in the ERP system for iOS and Android devices.

## Overview

The system uses Firebase Cloud Messaging (FCM) to send push notifications to mobile devices when database changes occur. This includes:

- New records being created
- Existing records being updated
- Records being deleted

## Prerequisites

1. Firebase Project

   - Create a project in [Firebase Console](https://console.firebase.google.com)
   - Set up Firebase Cloud Messaging
   - Get your Server Key and Sender ID from Project Settings > Cloud Messaging

2. Environment Configuration
   Add the following variables to your `.env` file:
   ```
   FCM_SERVER_KEY=your-fcm-server-key
   FCM_SENDER_ID=your-fcm-sender-id
   ```

## Mobile App Integration

### Device Registration

Register device tokens using the API endpoint:

```http
POST /api/notifications/register-device
Content-Type: application/json
Authorization: Bearer {user-token}

{
    "device_token": "fcm-device-token",
    "platform": "ios|android",
    "notification_preferences": {
        "leave_requests": true,
        "project_updates": true,
        "task_assignments": true
    }
}
```

### Update Preferences

Update notification preferences:

```http
PUT /api/notifications/update-preferences
Content-Type: application/json
Authorization: Bearer {user-token}

{
    "notification_preferences": {
        "leave_requests": false,
        "project_updates": true,
        "task_assignments": true
    }
}
```

### Deactivate Device

Deactivate a device token:

```http
POST /api/notifications/deactivate-device
Content-Type: application/json
Authorization: Bearer {user-token}

{
    "device_token": "fcm-device-token"
}
```

## Backend Implementation

### Using the NotifiesOnDatabaseChanges Trait

Add the trait to any model that should trigger notifications:

```php
use App\Traits\NotifiesOnDatabaseChanges;

class YourModel extends Model
{
    use NotifiesOnDatabaseChanges;

    // Optional: Override notification configuration
    public function getNotificationConfig(string $action): ?array
    {
        return [
            'title' => 'Custom Title',
            'body' => 'Custom message body',
            'data' => [
                'custom_key' => 'custom_value'
            ]
        ];
    }

    // Optional: Override notification recipients
    public function getNotificationRecipients(string $action): array
    {
        return [1, 2, 3]; // User IDs to notify
    }
}
```

### Manual Notification Sending

Use the PushNotificationService to send notifications manually:

```php
use App\Services\PushNotificationService;

$notificationService = app(PushNotificationService::class);

// Send to specific user
$notificationService->sendToUser(
    userId: 1,
    title: 'Notification Title',
    body: 'Notification message',
    data: ['key' => 'value']
);

// Send to multiple users
$notificationService->sendToUsers(
    userIds: [1, 2, 3],
    title: 'Notification Title',
    body: 'Notification message',
    data: ['key' => 'value']
);

// Send to specific platform
$notificationService->sendToPlatform(
    platform: 'ios',
    title: 'Notification Title',
    body: 'Notification message',
    data: ['key' => 'value']
);
```

## Testing

1. Use the Firebase Console to send test messages
2. Monitor notification delivery in the Laravel logs
3. Check FCM response codes for troubleshooting

## Troubleshooting

Common issues and solutions:

1. Notifications not sending

   - Verify FCM credentials in `.env`
   - Check Laravel logs for errors
   - Verify device token is valid

2. Notifications not receiving

   - Check device token registration
   - Verify app has notification permissions
   - Check device notification settings

3. Invalid token errors
   - Remove invalid tokens using deactivate endpoint
   - Update device token on app restart

## Security Considerations

1. Always validate user authentication before registering devices
2. Keep FCM credentials secure
3. Validate notification preferences
4. Implement rate limiting on notification endpoints
5. Clean up inactive device tokens periodically

## Best Practices

1. Keep notification payload small
2. Use data messages for handling notifications in the foreground
3. Implement retry logic for failed notifications
4. Clean up old device tokens
5. Monitor FCM quotas and limits
6. Implement notification grouping for better UX
7. Provide user control over notification types
