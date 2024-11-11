<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class SecurityAuditLog extends Model
{
  protected $fillable = [
    'user_id',
    'event_type',
    'ip_address',
    'location',
    'user_agent',
    'status',
    'details'
  ];

  protected $casts = [
    'details' => 'array'
  ];

  /**
   * Get the user that owns the audit log.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Create a new audit log entry.
   */
  public static function log($eventType, $status = 'success', $details = null)
  {
    return static::create([
      'user_id' => Auth::id(),
      'event_type' => $eventType,
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => $status,
      'details' => $details
    ]);
  }

  /**
   * Log a failed login attempt.
   */
  public static function logFailedLogin($email)
  {
    return static::create([
      'event_type' => 'failed_login',
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => 'failed',
      'details' => ['email' => $email]
    ]);
  }

  /**
   * Log a successful login.
   */
  public static function logSuccessfulLogin()
  {
    return static::create([
      'user_id' => Auth::id(),
      'event_type' => 'login',
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => 'success'
    ]);
  }

  /**
   * Log a logout event.
   */
  public static function logLogout()
  {
    return static::create([
      'user_id' => Auth::id(),
      'event_type' => 'logout',
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => 'success'
    ]);
  }

  /**
   * Log a password change.
   */
  public static function logPasswordChange()
  {
    return static::create([
      'user_id' => Auth::id(),
      'event_type' => 'password_change',
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => 'success'
    ]);
  }

  /**
   * Log a 2FA event.
   */
  public static function log2FAEvent($action, $status = 'success', $details = null)
  {
    return static::create([
      'user_id' => Auth::id(),
      'event_type' => '2fa_' . $action,
      'ip_address' => request()->ip(),
      'location' => 'Unknown', // Location will be determined by your location service
      'user_agent' => request()->userAgent(),
      'status' => $status,
      'details' => $details
    ]);
  }
}
