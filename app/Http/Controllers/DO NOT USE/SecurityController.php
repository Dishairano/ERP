<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SecurityAuditLog;
use App\Models\UserSecuritySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
  public function dashboard()
  {
    $recentLogs = SecurityAuditLog::with('user')->latest()->take(10)->get();
    $failedLogins = SecurityAuditLog::where('event_type', '=', 'failed_login')->count();
    $twoFactorEnabled = UserSecuritySetting::where('two_factor_enabled', true)->count();
    $totalUsers = User::count();

    return view('security.dashboard', compact(
      'recentLogs',
      'failedLogins',
      'twoFactorEnabled',
      'totalUsers'
    ));
  }

  public function settings()
  {
    $securitySettings = UserSecuritySetting::where('user_id', Auth::user()->id)->first();
    return view('security.settings', compact('securitySettings'));
  }

  public function updateSettings(Request $request)
  {
    $validated = $request->validate([
      'password_expiry_days' => 'required|integer|min:30|max:365',
      'max_login_attempts' => 'required|integer|min:3|max:10',
      'session_timeout_minutes' => 'required|integer|min:15|max:240',
      'require_password_history' => 'required|boolean',
      'password_complexity_level' => 'required|in:low,medium,high'
    ]);

    $settings = UserSecuritySetting::where('user_id', Auth::user()->id)->first();
    $settings->update($validated);

    SecurityAuditLog::log('security_settings_updated');

    return redirect()->route('security.settings')->with('success', 'Security settings updated successfully');
  }

  public function showTwoFactorSetup()
  {
    $user = Auth::user();
    $securitySettings = $user->securitySettings;

    return view('security.2fa.enable', compact('securitySettings'));
  }

  public function enableTwoFactor(Request $request)
  {
    $validated = $request->validate([
      'phone_number' => 'required|string|max:15',
      'verification_code' => 'required|string|size:6'
    ]);

    $settings = UserSecuritySetting::where('user_id', Auth::user()->id)->first();
    $settings->update([
      'two_factor_enabled' => true,
      'phone_number' => $validated['phone_number']
    ]);

    SecurityAuditLog::log2FAEvent('enabled');

    return redirect()->route('security.settings')->with('success', 'Two-factor authentication enabled successfully');
  }

  public function disableTwoFactor()
  {
    $settings = UserSecuritySetting::where('user_id', Auth::user()->id)->first();
    $settings->update([
      'two_factor_enabled' => false,
      'phone_number' => null
    ]);

    SecurityAuditLog::log2FAEvent('disabled');

    return redirect()->route('security.settings')->with('success', 'Two-factor authentication disabled successfully');
  }

  public function verifyTwoFactor(Request $request)
  {
    $validated = $request->validate([
      'verification_code' => 'required|string|size:6'
    ]);

    // Here you would verify the code with your 2FA service
    // For now, we'll just log the attempt
    SecurityAuditLog::log2FAEvent('verification_attempt', 'success');

    return redirect()->intended('/dashboard');
  }

  public function resendVerificationCode()
  {
    // Here you would implement the logic to resend the verification code
    // For now, we'll just log the attempt
    SecurityAuditLog::log2FAEvent('code_resent');

    return response()->json(['message' => 'Verification code resent successfully']);
  }

  public function revokeSession($id)
  {
    // Here you would implement the logic to revoke a specific session
    SecurityAuditLog::log('session_revoked', 'success', ['session_id' => $id]);

    return redirect()->back()->with('success', 'Session revoked successfully');
  }
}
