<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmLeaveRequestModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_leave_requests';

  protected $fillable = [
    'employee_id',
    'leave_type', // annual, sick, maternity, paternity, bereavement, unpaid, etc.
    'start_date',
    'end_date',
    'duration',
    'half_day',
    'start_half', // first, second (for half days)
    'reason',
    'attachments',
    'emergency_contact',
    'emergency_phone',
    'handover_notes',
    'status', // pending, approved, rejected, cancelled
    'approved_by',
    'approved_at',
    'rejected_by',
    'rejected_at',
    'rejection_reason',
    'cancellation_reason',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'duration' => 'decimal:1',
    'half_day' => 'boolean',
    'attachments' => 'array',
    'approved_at' => 'datetime',
    'rejected_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the leave request.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the user who approved the request.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the user who rejected the request.
   */
  public function rejector(): BelongsTo
  {
    return $this->belongsTo(User::class, 'rejected_by');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available leave types.
   */
  public static function getLeaveTypes(): array
  {
    return [
      'annual',
      'sick',
      'maternity',
      'paternity',
      'bereavement',
      'study',
      'marriage',
      'unpaid',
      'compensatory',
      'work_from_home',
      'other'
    ];
  }

  /**
   * Calculate the duration in days.
   */
  public function calculateDuration(): float
  {
    if ($this->half_day) {
      return 0.5;
    }

    return $this->start_date->diffInDays($this->end_date) + 1;
  }

  /**
   * Check if the leave is currently active.
   */
  public function isActive(): bool
  {
    $now = now();
    return $this->status === 'approved' &&
      $this->start_date->lte($now) &&
      $this->end_date->gte($now);
  }

  /**
   * Check if the leave is upcoming.
   */
  public function isUpcoming(): bool
  {
    return $this->status === 'approved' &&
      $this->start_date->gt(now());
  }

  /**
   * Check if the leave is completed.
   */
  public function isCompleted(): bool
  {
    return $this->status === 'approved' &&
      $this->end_date->lt(now());
  }

  /**
   * Check if the leave request can be cancelled.
   */
  public function canBeCancelled(): bool
  {
    return in_array($this->status, ['pending', 'approved']) &&
      $this->start_date->gt(now());
  }

  /**
   * Check if the leave request overlaps with another approved request.
   */
  public function hasOverlap(): bool
  {
    return static::where('employee_id', $this->employee_id)
      ->where('id', '!=', $this->id)
      ->where('status', 'approved')
      ->where(function ($query) {
        $query->whereBetween('start_date', [$this->start_date, $this->end_date])
          ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
          ->orWhere(function ($q) {
            $q->where('start_date', '<=', $this->start_date)
              ->where('end_date', '>=', $this->end_date);
          });
      })
      ->exists();
  }

  /**
   * Scope a query to only include pending requests.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include approved requests.
   */
  public function scopeApproved($query)
  {
    return $query->where('status', 'approved');
  }

  /**
   * Scope a query to only include rejected requests.
   */
  public function scopeRejected($query)
  {
    return $query->where('status', 'rejected');
  }

  /**
   * Scope a query to only include cancelled requests.
   */
  public function scopeCancelled($query)
  {
    return $query->where('status', 'cancelled');
  }

  /**
   * Scope a query to only include active leaves.
   */
  public function scopeActive($query)
  {
    $now = now();
    return $query->where('status', 'approved')
      ->where('start_date', '<=', $now)
      ->where('end_date', '>=', $now);
  }

  /**
   * Scope a query to only include upcoming leaves.
   */
  public function scopeUpcoming($query)
  {
    return $query->where('status', 'approved')
      ->where('start_date', '>', now());
  }

  /**
   * Scope a query to only include completed leaves.
   */
  public function scopeCompleted($query)
  {
    return $query->where('status', 'approved')
      ->where('end_date', '<', now());
  }

  /**
   * Scope a query to only include leaves of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('leave_type', $type);
  }

  /**
   * Scope a query to only include leaves within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->where(function ($q) use ($startDate, $endDate) {
      $q->whereBetween('start_date', [$startDate, $endDate])
        ->orWhereBetween('end_date', [$startDate, $endDate])
        ->orWhere(function ($q) use ($startDate, $endDate) {
          $q->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate);
        });
    });
  }
}
