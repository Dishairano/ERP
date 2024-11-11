<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeRegistration extends Model
{
  protected $fillable = [
    'user_id',
    'project_id',
    'task_id',
    'date',
    'hours',
    'description',
    'billable',
    'status',
    'rejection_reason',
    'approved_by',
    'rejected_by',
    'approved_at',
    'rejected_at',
    'week_number',
    'month'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'decimal:2',
    'billable' => 'boolean',
    'approved_at' => 'datetime',
    'rejected_at' => 'datetime',
    'week_number' => 'integer',
    'month' => 'integer'
  ];

  /**
   * Get the user that owns the time registration.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the project associated with the time registration.
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the task associated with the time registration.
   */
  public function task(): BelongsTo
  {
    return $this->belongsTo(Task::class);
  }

  /**
   * Get the user who approved the time registration.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the user who rejected the time registration.
   */
  public function rejector(): BelongsTo
  {
    return $this->belongsTo(User::class, 'rejected_by');
  }

  /**
   * Scope a query to only include pending registrations.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include approved registrations.
   */
  public function scopeApproved($query)
  {
    return $query->where('status', 'approved');
  }

  /**
   * Scope a query to only include rejected registrations.
   */
  public function scopeRejected($query)
  {
    return $query->where('status', 'rejected');
  }

  /**
   * Scope a query to only include billable registrations.
   */
  public function scopeBillable($query)
  {
    return $query->where('billable', true);
  }

  /**
   * Scope a query to filter by date range.
   */
  public function scopeDateRange($query, $start, $end)
  {
    return $query->whereBetween('date', [$start, $end]);
  }

  /**
   * Get the formatted hours with 2 decimal places.
   */
  public function getFormattedHoursAttribute(): string
  {
    return number_format($this->hours, 2);
  }

  /**
   * Get the status with proper formatting.
   */
  public function getFormattedStatusAttribute(): string
  {
    return ucfirst($this->status);
  }

  /**
   * Check if the time registration can be edited.
   */
  public function canBeEdited(): bool
  {
    return $this->status === 'pending';
  }

  /**
   * Check if the time registration is approved.
   */
  public function isApproved(): bool
  {
    return $this->status === 'approved';
  }

  /**
   * Check if the time registration is rejected.
   */
  public function isRejected(): bool
  {
    return $this->status === 'rejected';
  }
}
