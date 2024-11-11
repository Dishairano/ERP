<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoreLeaveTypeModal extends Model
{
  protected $table = 'leave_types';

  protected $fillable = [
    'name',
    'code',
    'description',
    'requires_approval',
    'paid'
  ];

  protected $casts = [
    'requires_approval' => 'boolean',
    'paid' => 'boolean'
  ];

  // Relationships
  public function policy(): HasOne
  {
    return $this->hasOne(CoreLeavePolicyModal::class, 'leave_type_id');
  }

  public function balances(): HasMany
  {
    return $this->hasMany(CoreLeaveBalanceModal::class, 'leave_type_id');
  }

  public function requests(): HasMany
  {
    return $this->hasMany(CoreLeaveRequestModal::class, 'leave_type_id');
  }

  // Scopes
  public function scopePaid($query)
  {
    return $query->where('paid', true);
  }

  public function scopeUnpaid($query)
  {
    return $query->where('paid', false);
  }

  public function scopeRequiresApproval($query)
  {
    return $query->where('requires_approval', true);
  }

  // Methods
  public function getUserBalance($userId, $year = null): float
  {
    $year = $year ?? date('Y');

    return $this->balances()
      ->where('user_id', $userId)
      ->where('year', $year)
      ->value('total_days') ?? 0;
  }

  public function getUserUsedDays($userId, $year = null): float
  {
    $year = $year ?? date('Y');

    return $this->balances()
      ->where('user_id', $userId)
      ->where('year', $year)
      ->value('used_days') ?? 0;
  }

  public function getUserPendingDays($userId, $year = null): float
  {
    $year = $year ?? date('Y');

    return $this->balances()
      ->where('user_id', $userId)
      ->where('year', $year)
      ->value('pending_days') ?? 0;
  }

  public function getUserRemainingDays($userId, $year = null): float
  {
    $year = $year ?? date('Y');
    $balance = $this->balances()
      ->where('user_id', $userId)
      ->where('year', $year)
      ->first();

    if (!$balance) {
      return 0;
    }

    return $balance->total_days - $balance->used_days - $balance->pending_days;
  }

  public function isEligibleForUser($userId): bool
  {
    if (!$this->policy) {
      return true;
    }

    $user = User::find($userId);
    if (!$user) {
      return false;
    }

    // Check minimum service days requirement
    if ($this->policy->min_service_days_required > 0) {
      $serviceDays = now()->diffInDays($user->created_at);
      if ($serviceDays < $this->policy->min_service_days_required) {
        return false;
      }
    }

    return true;
  }

  public function initializeUserBalance($userId, $year = null): void
  {
    $year = $year ?? date('Y');

    // Check if balance already exists
    $exists = $this->balances()
      ->where('user_id', $userId)
      ->where('year', $year)
      ->exists();

    if (!$exists && $this->policy) {
      $this->balances()->create([
        'user_id' => $userId,
        'year' => $year,
        'total_days' => $this->policy->days_per_year,
        'used_days' => 0,
        'pending_days' => 0,
        'carried_forward_days' => 0
      ]);
    }
  }

  protected static function boot()
  {
    parent::boot();

    static::deleting(function ($leaveType) {
      // Prevent deletion if there are active leave requests
      if ($leaveType->requests()->where('status', 'pending')->exists()) {
        return false;
      }
    });
  }
}
