<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreLeaveBalanceModal extends Model
{
  protected $table = 'leave_balances';

  protected $fillable = [
    'user_id',
    'leave_type_id',
    'year',
    'total_days',
    'used_days',
    'pending_days',
    'carried_forward_days'
  ];

  protected $casts = [
    'year' => 'integer',
    'total_days' => 'decimal:2',
    'used_days' => 'decimal:2',
    'pending_days' => 'decimal:2',
    'carried_forward_days' => 'decimal:2'
  ];

  // Relationships
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function leaveType(): BelongsTo
  {
    return $this->belongsTo(CoreLeaveTypeModal::class, 'leave_type_id');
  }

  // Scopes
  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeForYear($query, $year)
  {
    return $query->where('year', $year);
  }

  public function scopeForLeaveType($query, $leaveTypeId)
  {
    return $query->where('leave_type_id', $leaveTypeId);
  }

  // Accessors
  public function getRemainingDaysAttribute(): float
  {
    return $this->total_days - $this->used_days - $this->pending_days;
  }

  public function getAvailableDaysAttribute(): float
  {
    return $this->total_days - $this->used_days;
  }

  // Methods
  public function addDays(float $days): bool
  {
    return $this->update([
      'total_days' => $this->total_days + $days
    ]);
  }

  public function deductDays(float $days): bool
  {
    if ($this->remaining_days < $days) {
      return false;
    }

    return $this->update([
      'used_days' => $this->used_days + $days
    ]);
  }

  public function addPendingDays(float $days): bool
  {
    if ($this->remaining_days < $days) {
      return false;
    }

    return $this->update([
      'pending_days' => $this->pending_days + $days
    ]);
  }

  public function removePendingDays(float $days): bool
  {
    if ($this->pending_days < $days) {
      return false;
    }

    return $this->update([
      'pending_days' => $this->pending_days - $days
    ]);
  }

  public function carryForward(float $days, int $toYear): bool
  {
    // Check if carry forward is allowed
    if (!$this->leaveType->policy->can_carry_forward) {
      return false;
    }

    // Check carry forward limit
    $maxCarryForward = $this->leaveType->policy->max_carry_forward_days ?? PHP_FLOAT_MAX;
    $daysToCarry = min($days, $maxCarryForward);

    // Create or update balance for next year
    $nextYearBalance = self::firstOrNew([
      'user_id' => $this->user_id,
      'leave_type_id' => $this->leave_type_id,
      'year' => $toYear
    ]);

    $nextYearBalance->fill([
      'total_days' => ($nextYearBalance->total_days ?? 0) + $daysToCarry,
      'carried_forward_days' => ($nextYearBalance->carried_forward_days ?? 0) + $daysToCarry
    ]);

    return $nextYearBalance->save();
  }

  public function hasEnoughBalance(float $requestedDays): bool
  {
    return $this->remaining_days >= $requestedDays;
  }

  public function canRequestLeave(float $requestedDays, \DateTime $startDate): bool
  {
    // Check if there's enough balance
    if (!$this->hasEnoughBalance($requestedDays)) {
      return false;
    }

    // Check if the policy allows this duration
    if (!$this->leaveType->policy->isEligibleForDuration($requestedDays)) {
      return false;
    }

    // Check if notice period is met
    if (!$this->leaveType->policy->hasMetNoticePeriod($startDate)) {
      return false;
    }

    return true;
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      // Ensure values don't go below 0
      $model->used_days = max(0, $model->used_days);
      $model->pending_days = max(0, $model->pending_days);
      $model->carried_forward_days = max(0, $model->carried_forward_days);
    });
  }
}
