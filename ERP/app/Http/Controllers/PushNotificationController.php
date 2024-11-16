<?php

namespace App\Http\Controllers;

use App\Models\PushNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
  /**
   * Register or update device token
   */
  public function registerDevice(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'device_token' => 'required|string',
      'platform' => 'required|in:ios,android',
      'notification_preferences' => 'nullable|array'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors()
      ], 422);
    }

    try {
      $setting = PushNotificationSetting::updateOrCreate(
        [
          'user_id' => Auth::id(),
          'device_token' => $request->device_token
        ],
        [
          'platform' => $request->platform,
          'notification_preferences' => $request->notification_preferences,
          'is_active' => true
        ]
      );

      return response()->json([
        'success' => true,
        'message' => 'Device registered successfully',
        'data' => $setting
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to register device',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Update notification preferences
   */
  public function updatePreferences(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'notification_preferences' => 'required|array'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors()
      ], 422);
    }

    try {
      $settings = PushNotificationSetting::where('user_id', Auth::id())
        ->update([
          'notification_preferences' => $request->notification_preferences
        ]);

      return response()->json([
        'success' => true,
        'message' => 'Preferences updated successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to update preferences',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Deactivate device token
   */
  public function deactivateDevice(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'device_token' => 'required|string'
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validator->errors()
      ], 422);
    }

    try {
      PushNotificationSetting::where('user_id', Auth::id())
        ->where('device_token', $request->device_token)
        ->update(['is_active' => false]);

      return response()->json([
        'success' => true,
        'message' => 'Device deactivated successfully'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to deactivate device',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Get user's notification settings
   */
  public function getSettings()
  {
    try {
      $settings = PushNotificationSetting::where('user_id', Auth::id())
        ->get();

      return response()->json([
        'success' => true,
        'data' => $settings
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Failed to fetch notification settings',
        'error' => $e->getMessage()
      ], 500);
    }
  }
}
