<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
  public function index()
  {
    $settings = [
      'email_notifications' => true,
      'push_notifications' => true,
      'notification_types' => [
        'project_updates' => true,
        'task_assignments' => true,
        'due_dates' => true,
        'system_updates' => false,
      ],
    ];

    return view('settings.notifications.index', compact('settings'));
  }

  public function update(Request $request)
  {
    $validated = $request->validate([
      'email_notifications' => 'required|boolean',
      'push_notifications' => 'required|boolean',
      'notification_types' => 'required|array',
      'notification_types.*' => 'boolean',
    ]);

    // Update notification settings in database or config
    // This is a placeholder - implement actual storage method

    return redirect()->route('settings.notifications')
      ->with('success', 'Notification settings updated successfully');
  }
}
