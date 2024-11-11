<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSecuritySetting extends Model
{
  protected $fillable = [
    'user_id',
    'two_factor_enabled',
    'phone_number',
    'password_expiry_days',
    'max_login_attempts',
    'session_timeout_minutes',
    'require_password_history',
    'password_complexity_level',
    'failed_login_attempts',
    'last_password_change',
    'last_login_at'
  ];

  protected $casts = [
    'two_factor_enabled' => 'boolean',
    'require_password_history' => 'boolean',
    'last_password_change' => 'datetime',
    'last_login_at' => 'datetime'
  ];

  /**
   * Get the user that owns the security settings.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Check if password needs to be changed.
   */
  public function passwordNeedsChange(): bool
  {
    if (!$this->last_password_change) {
      return true;
    }

    return $this->last_password_change->addDays($this->password_expiry_days)->isPast();
  }

  /**
   * Check if account is locked due to too many failed attempts.
   */
  public function isLocked(): bool
  {
    return $this->failed_login_attempts >= $this->max_login_attempts;
  }

  /**
   * Increment failed login attempts.
   */
  public function incrementFailedAttempts(): void
  {
    $this->increment('failed_login_attempts');
  }

  /**
   * Reset failed login attempts.
   */
  public function resetFailedAttempts(): void
  {
    $this->update(['failed_login_attempts' => 0]);
  }

  /**
   * Update last login timestamp.
   */
  public function updateLastLogin(): void
  {
    $this->update(['last_login_at' => now()]);
  }

  /**
   * Get password complexity requirements.
   */
  public function getPasswordRequirements(): array
  {
    $requirements = [
      'low' => [
        'min_length' => 8,
        'require_numbers' => false,
        'require_symbols' => false,
        'require_mixed_case' => false
      ],
      'medium' => [
        'min_length' => 10,
        'require_numbers' => true,
        'require_symbols' => false,
        'require_mixed_case' => true
      ],
      'high' => [
        'min_length' => 12,
        'require_numbers' => true,
        'require_symbols' => true,
        'require_mixed_case' => true
      ]
    ];

    return $requirements[$this->password_complexity_level] ?? $requirements['high'];
  }

  /**
   * Check if a password meets the complexity requirements.
   */
  public function passwordMeetsRequirements(string $password): bool
  {
    $requirements = $this->getPasswordRequirements();

    if (strlen($password) < $requirements['min_length']) {
      return false;
    }

    if ($requirements['require_numbers'] && !preg_match('/[0-9]/', $password)) {
      return false;
    }

    if ($requirements['require_symbols'] && !preg_match('/[^A-Za-z0-9]/', $password)) {
      return false;
    }

    if ($requirements['require_mixed_case'] && (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password))) {
      return false;
    }

    return true;
  }
}
