<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CoreSettingModal;
use App\Exports\AuditTrailExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CoreSettingController extends Controller
{
  public function index()
  {
    // Redirect to general settings by default
    return redirect()->route('settings.general');
  }

  public function general()
  {
    $settings = CoreSettingModal::where('group', '=', 'general')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.general', compact('settings'));
  }

  public function updateGeneral(Request $request)
  {
    $validated = $request->validate([
      'site_name' => 'required|string|max:255',
      'site_description' => 'nullable|string',
      'timezone' => 'required|string',
      'date_format' => 'required|string',
      'time_format' => 'required|string',
      'currency' => 'required|string',
      'language' => 'required|string'
    ]);

    foreach ($validated as $key => $value) {
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'general'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'General settings updated successfully');
  }

  public function company()
  {
    $settings = CoreSettingModal::where('group', '=', 'company')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.company', compact('settings'));
  }

  public function updateCompany(Request $request)
  {
    $validated = $request->validate([
      'company_name' => 'required|string|max:255',
      'company_address' => 'required|string',
      'company_email' => 'required|email',
      'company_phone' => 'required|string',
      'company_website' => 'nullable|url',
      'company_logo' => 'nullable|image|max:2048',
      'tax_id' => 'nullable|string',
      'registration_number' => 'nullable|string'
    ]);

    if ($request->hasFile('company_logo')) {
      $path = $request->file('company_logo')->store('company', 'public');
      $validated['company_logo'] = $path;
    }

    foreach ($validated as $key => $value) {
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'company'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Company profile updated successfully');
  }

  public function notifications()
  {
    $settings = CoreSettingModal::where('group', '=', 'notifications')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.notifications', compact('settings'));
  }

  public function updateNotifications(Request $request)
  {
    $validated = $request->validate([
      'email_notifications' => 'boolean',
      'push_notifications' => 'boolean',
      'notification_frequency' => 'required|in:instant,hourly,daily,weekly',
      'notification_types.*' => 'boolean'
    ]);

    foreach ($validated as $key => $value) {
      if (is_array($value)) {
        $value = json_encode($value);
      }
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'notifications'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Notification settings updated successfully');
  }

  public function integrations()
  {
    $settings = CoreSettingModal::where('group', '=', 'integrations')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.integrations', compact('settings'));
  }

  public function updateIntegrations(Request $request)
  {
    $validated = $request->validate([
      'google_analytics_id' => 'nullable|string',
      'smtp_host' => 'nullable|string',
      'smtp_port' => 'nullable|numeric',
      'smtp_username' => 'nullable|string',
      'smtp_password' => 'nullable|string',
      'aws_access_key' => 'nullable|string',
      'aws_secret_key' => 'nullable|string',
      'aws_region' => 'nullable|string',
      'aws_bucket' => 'nullable|string'
    ]);

    foreach ($validated as $key => $value) {
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'integrations'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Integration settings updated successfully');
  }

  public function backup()
  {
    $backups = Storage::disk('backups')->files();
    return view('content.settings.backup', compact('backups'));
  }

  public function createBackup()
  {
    try {
      // Implement backup creation logic
      $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.zip';
      // Artisan::call('backup:run');
      return redirect()->back()->with('success', 'Backup created successfully');
    } catch (\Exception $e) {
      Log::error('Backup creation failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Backup creation failed');
    }
  }

  public function restoreBackup(Request $request)
  {
    $validated = $request->validate([
      'backup_file' => 'required|string'
    ]);

    try {
      // Implement backup restoration logic
      return redirect()->back()->with('success', 'Backup restored successfully');
    } catch (\Exception $e) {
      Log::error('Backup restoration failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Backup restoration failed');
    }
  }

  public function roles()
  {
    $roles = DB::table('roles')->get();
    $permissions = DB::table('permissions')->get();
    return view('content.settings.roles', compact('roles', 'permissions'));
  }

  public function storeRole(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:roles,name',
      'permissions' => 'required|array',
      'permissions.*' => 'exists:permissions,id'
    ]);

    DB::table('roles')->insert([
      'name' => $validated['name'],
      'created_at' => now(),
      'updated_at' => now()
    ]);

    $roleId = DB::getPdo()->lastInsertId();

    foreach ($validated['permissions'] as $permissionId) {
      DB::table('role_has_permissions')->insert([
        'role_id' => $roleId,
        'permission_id' => $permissionId
      ]);
    }

    return redirect()->back()->with('success', 'Role created successfully');
  }

  public function updateRole(Request $request, $id)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255|unique:roles,name,' . $id,
      'permissions' => 'required|array',
      'permissions.*' => 'exists:permissions,id'
    ]);

    DB::table('roles')
      ->where('id', $id)
      ->update([
        'name' => $validated['name'],
        'updated_at' => now()
      ]);

    // Sync permissions
    DB::table('role_has_permissions')->where('role_id', $id)->delete();
    foreach ($validated['permissions'] as $permissionId) {
      DB::table('role_has_permissions')->insert([
        'role_id' => $id,
        'permission_id' => $permissionId
      ]);
    }

    return redirect()->back()->with('success', 'Role updated successfully');
  }

  public function deleteRole($id)
  {
    DB::table('role_has_permissions')->where('role_id', $id)->delete();
    DB::table('roles')->where('id', $id)->delete();

    return redirect()->back()->with('success', 'Role deleted successfully');
  }

  public function users()
  {
    $users = User::paginate(10);
    $roles = DB::table('roles')->get();
    return view('content.settings.users', compact('users', 'roles'));
  }

  public function storeUser(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'roles' => 'required|array',
      'roles.*' => 'exists:roles,id'
    ]);

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => bcrypt($validated['password'])
    ]);

    foreach ($validated['roles'] as $roleId) {
      DB::table('model_has_roles')->insert([
        'role_id' => $roleId,
        'model_type' => User::class,
        'model_id' => $user->id
      ]);
    }

    return redirect()->back()->with('success', 'User created successfully');
  }

  public function updateUser(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'password' => 'nullable|string|min:8|confirmed',
      'roles' => 'required|array',
      'roles.*' => 'exists:roles,id'
    ]);

    $userData = [
      'name' => $validated['name'],
      'email' => $validated['email']
    ];

    if (!empty($validated['password'])) {
      $userData['password'] = bcrypt($validated['password']);
    }

    $user->update($userData);

    // Sync roles
    DB::table('model_has_roles')
      ->where('model_id', $user->id)
      ->where('model_type', User::class)
      ->delete();

    foreach ($validated['roles'] as $roleId) {
      DB::table('model_has_roles')->insert([
        'role_id' => $roleId,
        'model_type' => User::class,
        'model_id' => $user->id
      ]);
    }

    return redirect()->back()->with('success', 'User updated successfully');
  }

  public function deleteUser($id)
  {
    $user = User::findOrFail($id);

    DB::table('model_has_roles')
      ->where('model_id', $user->id)
      ->where('model_type', User::class)
      ->delete();

    $user->delete();

    return redirect()->back()->with('success', 'User deleted successfully');
  }

  public function auditLog()
  {
    $logs = DB::table('audit_logs')
      ->orderBy('created_at', 'desc')
      ->paginate(15);

    return view('content.settings.audit-log', compact('logs'));
  }

  public function exportAuditLog()
  {
    return Excel::download(new AuditTrailExport, 'audit-log-' . now()->format('Y-m-d') . '.xlsx');
  }

  public function security()
  {
    $settings = CoreSettingModal::where('group', '=', 'security')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.security', compact('settings'));
  }

  public function updateSecurity(Request $request)
  {
    $validated = $request->validate([
      'password_policy' => 'required|array',
      'password_policy.min_length' => 'required|integer|min:8',
      'password_policy.require_uppercase' => 'boolean',
      'password_policy.require_numbers' => 'boolean',
      'password_policy.require_symbols' => 'boolean',
      'password_expiry_days' => 'nullable|integer',
      'max_login_attempts' => 'required|integer',
      'lockout_duration' => 'required|integer',
      'session_timeout' => 'required|integer',
      'two_factor_auth' => 'boolean',
      'ip_whitelist' => 'nullable|array',
      'ip_whitelist.*' => 'ip'
    ]);

    foreach ($validated as $key => $value) {
      if (is_array($value)) {
        $value = json_encode($value);
      }
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'security'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Security settings updated successfully');
  }

  public function localization()
  {
    $settings = CoreSettingModal::where('group', '=', 'localization')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.localization', compact('settings'));
  }

  public function updateLocalization(Request $request)
  {
    $validated = $request->validate([
      'default_language' => 'required|string',
      'available_languages' => 'required|array',
      'default_timezone' => 'required|string',
      'date_format' => 'required|string',
      'time_format' => 'required|string',
      'first_day_of_week' => 'required|integer|between:0,6',
      'currency_code' => 'required|string|size:3',
      'currency_symbol' => 'required|string',
      'thousand_separator' => 'required|string',
      'decimal_separator' => 'required|string',
      'number_of_decimals' => 'required|integer|between:0,4'
    ]);

    foreach ($validated as $key => $value) {
      if (is_array($value)) {
        $value = json_encode($value);
      }
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'localization'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Localization settings updated successfully');
  }

  public function email()
  {
    $settings = CoreSettingModal::where('group', '=', 'email')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.email', compact('settings'));
  }

  public function updateEmail(Request $request)
  {
    $validated = $request->validate([
      'mail_driver' => 'required|string',
      'mail_host' => 'required|string',
      'mail_port' => 'required|integer',
      'mail_username' => 'required|string',
      'mail_password' => 'required|string',
      'mail_encryption' => 'required|string',
      'mail_from_address' => 'required|email',
      'mail_from_name' => 'required|string',
      'email_signature' => 'nullable|string'
    ]);

    foreach ($validated as $key => $value) {
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'email'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Email settings updated successfully');
  }

  public function testEmail(Request $request)
  {
    $request->validate([
      'test_email' => 'required|email'
    ]);

    try {
      Mail::raw('Test email from ' . config('app.name'), function ($message) use ($request) {
        $message->to($request->test_email)
          ->subject('Test Email');
      });

      return redirect()->back()->with('success', 'Test email sent successfully');
    } catch (\Exception $e) {
      Log::error('Test email failed: ' . $e->getMessage());
      return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
    }
  }

  public function workflow()
  {
    $settings = CoreSettingModal::where('group', '=', 'workflow')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.workflow', compact('settings'));
  }

  public function updateWorkflow(Request $request)
  {
    $validated = $request->validate([
      'approval_chains' => 'required|array',
      'approval_chains.*.name' => 'required|string',
      'approval_chains.*.steps' => 'required|array',
      'approval_chains.*.steps.*.role' => 'required|exists:roles,id',
      'default_approval_chain' => 'required|string',
      'auto_approval_threshold' => 'nullable|numeric',
      'escalation_time' => 'nullable|integer',
      'reminder_frequency' => 'nullable|integer'
    ]);

    foreach ($validated as $key => $value) {
      if (is_array($value)) {
        $value = json_encode($value);
      }
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'workflow'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'Workflow settings updated successfully');
  }

  public function api()
  {
    $settings = CoreSettingModal::where('group', '=', 'api')->get()
      ->pluck('value', 'key')
      ->toArray();

    return view('content.settings.api', compact('settings'));
  }

  public function updateApi(Request $request)
  {
    $validated = $request->validate([
      'api_enabled' => 'boolean',
      'rate_limiting' => 'required|array',
      'rate_limiting.enabled' => 'boolean',
      'rate_limiting.attempts' => 'required_if:rate_limiting.enabled,true|integer',
      'rate_limiting.decay_minutes' => 'required_if:rate_limiting.enabled,true|integer',
      'allowed_origins' => 'nullable|array',
      'allowed_origins.*' => 'url',
      'webhook_url' => 'nullable|url',
      'webhook_events' => 'nullable|array'
    ]);

    foreach ($validated as $key => $value) {
      if (is_array($value)) {
        $value = json_encode($value);
      }
      CoreSettingModal::updateOrCreate(
        ['key' => $key, 'group' => 'api'],
        ['value' => $value]
      );
    }

    return redirect()->back()->with('success', 'API settings updated successfully');
  }

  public function generateApiKey()
  {
    $apiKey = Str::random(32);

    CoreSettingModal::updateOrCreate(
      ['key' => 'api_key', 'group' => 'api'],
      ['value' => $apiKey]
    );

    return redirect()->back()->with('success', 'New API key generated successfully');
  }
}
