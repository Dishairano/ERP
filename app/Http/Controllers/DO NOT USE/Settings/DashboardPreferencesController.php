<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardPreference;

class DashboardPreferencesController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function updatePreferences(Request $request)
  {
    $validated = $request->validate([
      'preferences' => 'required|array',
      'preferences.theme' => 'required|string|in:light,dark'
    ]);

    $user = $request->user();
    $preferences = $user->dashboardPreferences;

    if (!$preferences) {
      $preferences = new DashboardPreference([
        'preferences' => $validated['preferences']
      ]);
      $preferences->user()->associate($user);
      $preferences->save();
    } else {
      $currentPrefs = $preferences->preferences;
      $preferences->update([
        'preferences' => array_merge($currentPrefs, $validated['preferences'])
      ]);
    }

    return response()->json(['message' => 'Preferences updated successfully']);
  }
}
