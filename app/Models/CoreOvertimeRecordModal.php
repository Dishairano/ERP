<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreOvertimeRecordModal extends Model
{
  protected $table = 'overtime_records';

  protected $fillable = [
    'user_id',
    'schedule_id',
    'date',
    'start_time',
    'end_time',
    'hours',
    'rate_multiplier',
    'status',
    'approved_by',
    'approved_at',
    'reason'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'hours' => 'decimal:2',
    'rate_multiplier' => 'decimal:2',
    'approved_at' => 'datetime'
  ];

  // Relationships
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function schedule(): BelongsTo
  {
    return $this->belongsTo(CoreEmployeeScheduleModal::class, 'schedule_id');
  }

  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  // Scopes
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  public function scopeApproved($query)
  {
    return $query->where('status', 'approved');
  }

  public function scopeRejected($query)
  {
    return $query->where('status', 'rejected');
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeForDate($query, $date)
  {
    return $query->whereDate('date', $date);
  }

  public function scopeForDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  // Accessors
  public function getStatusBadgeAttribute(): string
  {
    return match ($this->status) {
      'approved' => '<span class="badge badge-success">Approved</span>',
      'rejected' => '<span class="badge badge-danger">Rejected</span>',
      default => '<span class="badge badge-warning">Pending</span>'
    };
  }

  public function getOvertimePayAttribute(): float
  {
    // Assuming base hourly rate is stored in user's settings or HR records
    $baseRate = $this->user->hourly_rate ?? 0;
    return $this->hours * $baseRate * $this->rate_multiplier;
  }

  public function getDurationAttribute(): string
  {
    return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
  }

  // Methods
  public function approve(int $approverId): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    return $this->update([
      'status' => 'approved',
      'approved_by' => $approverId,
      'approved_at' => now()
    ]);
  }

  public function reject(int $approverId, string $reason = null): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    return $this->update([
      'status' => 'rejected',
      'approved_by' => $approverId,
      'approved_at' => now(),
      'reason' => $reason
    ]);
  }

  public function updateHours(float $hours): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    return $this->update(['hours' => $hours]);
  }

  public function updateRateMultiplier(float $multiplier): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    return $this->update(['rate_multiplier' => $multiplier]);
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      // Calculate hours if not provided
      if (!$model->hours) {
        $start = new \DateTime($model->start_time);
        $end = new \DateTime($model->end_time);
        $interval = $start->diff($end);
        $model->hours = $interval->h + ($interval->i / 60);
      }

      // Set default rate multiplier if not provided
      if (!$model->rate_multiplier) {
        $model->rate_multiplier = 1.5; // Standard overtime rate
      }
    });
  }
}
