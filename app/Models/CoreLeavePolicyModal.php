<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreLeavePolicyModal extends Model
{
  protected $table = 'leave_policies';

  protected $fillable = [
    'leave_type_id',
    'days_per_year',
    'can_carry_forward',
    'max_carry_forward_days',
    'min_service_days_required',
    'max_consecutive_days',
    'notice_days_required'
  ];

  protected $casts = [
    'days_per_year' => 'decimal:2',
    'can_carry_forward' => 'boolean',
    'max_carry_forward_days' => 'decimal:2',
    'min_service_days_required' => 'integer',
    'max_consecutive_days' => 'integer',
    'notice_days_required' => 'integer'
  ];

  // Relationships
  public function leaveType(): BelongsTo
  {
    return $this->belongsTo(CoreLeaveTypeModal::class, 'leave_type_id');
  }

  // Methods
  public function isEligibleForDuration(int $days): bool
  {
    if (!$this->max_consecutive_days) {
      return true;
    }

    return $days <= $this->max_consecutive_days;
  }

  public function hasMetNoticePeriod(\DateTime $startDate): bool
  {
    if (!$this->notice_days_required) {
      return true;
    }

    $today = new \DateTime();
    $daysUntilStart = $today->diff($startDate)->days;

    return $daysUntilStart >= $this->notice_days_required;
  }

  public function getCarryForwardLimit(): ?float
  {
    if (!$this->can_carry_forward) {
      return 0;
    }

    return $this->max_carry_forward_days ?? $this->days_per_year;
  }

  public function calculateProRatedDays(\DateTime $startDate, \DateTime $endDate = null): float
  {
    $endDate = $endDate ?? new \DateTime('last day of December');
    $startOfYear = new \DateTime('first day of January');
    $daysInYear = $startOfYear->diff(new \DateTime('last day of December'))->days + 1;

    $daysActive = $startDate->diff($endDate)->days + 1;
    $proRatedDays = ($this->days_per_year / $daysInYear) * $daysActive;

    return round($proRatedDays, 2);
  }
}
