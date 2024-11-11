<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralSettingsController extends Controller
{
  public function index()
  {
    $settings = [
      'site_name' => config('app.name'),
      'timezone' => config('app.timezone'),
      'date_format' => config('app.date_format', 'Y-m-d'),
      'time_format' => config('app.time_format', 'H:i'),
      'language' => config('app.locale'),
    ];

    return view('settings.general.index', compact('settings'));
  }

  public function update(Request $request)
  {
    $validated = $request->validate([
      'site_name' => 'required|string|max:255',
      'timezone' => 'required|string|timezone',
      'date_format' => 'required|string|max:20',
      'time_format' => 'required|string|max:20',
      'language' => 'required|string|max:10',
    ]);

    // Update settings in database or config
    // This is a placeholder - implement actual storage method

    return redirect()->route('settings.general')
      ->with('success', 'General settings updated successfully');
  }
}
