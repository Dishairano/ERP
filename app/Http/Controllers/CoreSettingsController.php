<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\CoreSettingModal;

class CoreSettingsController extends Controller
{
  /**
   * Display general settings page.
   */
  public function general()
  {
    $settings = CoreSettingModal::getByGroup('general');
    return view('core.settings.general', compact('settings'));
  }

  /**
   * Update general settings.
   */
  public function updateGeneral(Request $request)
  {
    $validated = $request->validate([
      'company_name' => 'required|string|max:255',
      'timezone' => 'required|string|max:100',
      'date_format' => 'required|string|max:50',
      'time_format' => 'required|string|max:50',
      'currency' => 'required|string|max:10',
      'fiscal_year_start' => 'required|date',
      'language' => 'required|string|max:10',
    ]);

    foreach ($validated as $key => $value) {
      CoreSettingModal::set($key, $value, 'general');
    }

    return redirect()
      ->route('settings.general')
      ->with('success', 'General settings updated successfully');
  }

  /**
   * Display company profile page.
   */
  public function company()
  {
    $settings = CoreSettingModal::getByGroup('company');
    return view('core.settings.company', compact('settings'));
  }

  /**
   * Update company profile.
   */
  public function updateCompany(Request $request)
  {
    $validated = $request->validate([
      'company_name' => 'required|string|max:255',
      'company_address' => 'required|string',
      'company_city' => 'required|string|max:100',
      'company_state' => 'required|string|max:100',
      'company_country' => 'required|string|max:100',
      'company_postal_code' => 'required|string|max:20',
      'company_phone' => 'required|string|max:50',
      'company_email' => 'required|email|max:255',
      'company_website' => 'nullable|url|max:255',
      'company_tax_number' => 'nullable|string|max:50',
      'company_registration_number' => 'nullable|string|max:50',
      'company_logo' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('company_logo')) {
      $path = $request->file('company_logo')->store('company', 'public');
      $validated['company_logo'] = $path;
    }

    foreach ($validated as $key => $value) {
      CoreSettingModal::set($key, $value, 'company');
    }

    return redirect()
      ->route('settings.company')
      ->with('success', 'Company profile updated successfully');
  }

  /**
   * Display notifications settings page.
   */
  public function notifications()
  {
    $settings = CoreSettingModal::getByGroup('notifications');
    return view('core.settings.notifications', compact('settings'));
  }

  /**
   * Update notifications settings.
   */
  public function updateNotifications(Request $request)
  {
    $validated = $request->validate([
      'email_notifications' => 'required|boolean',
      'push_notifications' => 'required|boolean',
      'notification_types' => 'required|array',
      'notification_types.*' => 'string|in:project,task,risk,finance,hrm',
    ]);

    foreach ($validated as $key => $value) {
      CoreSettingModal::set($key, $value, 'notifications');
    }

    return redirect()
      ->route('settings.notifications')
      ->with('success', 'Notification settings updated successfully');
  }

  /**
   * Display integrations settings page.
   */
  public function integrations()
  {
    $settings = CoreSettingModal::getByGroup('integrations');
    return view('core.settings.integrations', compact('settings'));
  }

  /**
   * Update integration settings.
   */
  public function updateIntegrations(Request $request)
  {
    $validated = $request->validate([
      'integrations' => 'required|array',
      'integrations.*.name' => 'required|string|max:255',
      'integrations.*.api_key' => 'required|string|max:255',
      'integrations.*.api_secret' => 'nullable|string|max:255',
      'integrations.*.status' => 'required|boolean',
    ]);

    CoreSettingModal::set('integrations', $validated['integrations'], 'integrations');

    return redirect()
      ->route('settings.integrations')
      ->with('success', 'Integration settings updated successfully');
  }

  /**
   * Display backup settings page.
   */
  public function backup()
  {
    $backups = collect(Storage::disk('backups')->files())
      ->filter(function ($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
      })
      ->map(function ($file) {
        return [
          'name' => $file,
          'size' => Storage::disk('backups')->size($file),
          'date' => Storage::disk('backups')->lastModified($file),
        ];
      });

    return view('core.settings.backup', compact('backups'));
  }

  /**
   * Create new backup.
   */
  public function createBackup()
  {
    // Implement backup creation logic
    // This is just a placeholder
    $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.zip';

    // Backup creation would go here

    return redirect()
      ->route('settings.backup')
      ->with('success', 'Backup created successfully');
  }

  /**
   * Download backup file.
   */
  public function downloadBackup($filename)
  {
    if (Storage::disk('backups')->exists($filename)) {
      return response()->download(
        Storage::disk('backups')->path($filename)
      );
    }

    return redirect()
      ->route('settings.backup')
      ->with('error', 'Backup file not found');
  }

  /**
   * Delete backup file.
   */
  public function deleteBackup($filename)
  {
    if (Storage::disk('backups')->exists($filename)) {
      Storage::disk('backups')->delete($filename);
      return redirect()
        ->route('settings.backup')
        ->with('success', 'Backup deleted successfully');
    }

    return redirect()
      ->route('settings.backup')
      ->with('error', 'Backup file not found');
  }
}
