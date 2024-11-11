<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreLeaveRequestModal extends Model
{
  use SoftDeletes;

  protected $table = 'leave_requests';

  protected $fillable = [
    'user_id',
    'leave_type_id',
    'start_date',
    'end_date',
    'total_days',
    'reason',
    'status',
    'approved_by',
    'approved_at',
    'rejection_reason'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'total_days' => 'decimal:2',
    'approved_at' => 'datetime'
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

  public function scopeForLeaveType($query, $leaveTypeId)
  {
    return $query->where('leave_type_id', $leaveTypeId);
  }

  public function scopeForDateRange($query, $startDate, $endDate)
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

  // Accessors
  public function getStatusBadgeAttribute(): string
  {
    return match ($this->status) {
      'approved' => '<span class="badge badge-success">Approved</span>',
      'rejected' => '<span class="badge badge-danger">Rejected</span>',
      default => '<span class="badge badge-warning">Pending</span>'
    };
  }

  public function getDateRangeAttribute(): string
  {
    return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
  }

  // Methods
  public function approve(int $approverId): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    // Get user's leave balance
    $balance = CoreLeaveBalanceModal::where([
      'user_id' => $this->user_id,
      'leave_type_id' => $this->leave_type_id,
      'year' => $this->start_date->year
    ])->first();

    if (!$balance || !$balance->hasEnoughBalance($this->total_days)) {
      return false;
    }

    // Update leave balance
    $balance->removePendingDays($this->total_days);
    $balance->deductDays($this->total_days);

    // Update request status
    return $this->update([
      'status' => 'approved',
      'approved_by' => $approverId,
      'approved_at' => now()
    ]);
  }

  public function reject(int $approverId, string $reason): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    // Get user's leave balance
    $balance = CoreLeaveBalanceModal::where([
      'user_id' => $this->user_id,
      'leave_type_id' => $this->leave_type_id,
      'year' => $this->start_date->year
    ])->first();

    if ($balance) {
      $balance->removePendingDays($this->total_days);
    }

    // Update request status
    return $this->update([
      'status' => 'rejected',
      'approved_by' => $approverId,
      'approved_at' => now(),
      'rejection_reason' => $reason
    ]);
  }

  public function cancel(): bool
  {
    if ($this->status !== 'pending') {
      return false;
    }

    // Get user's leave balance
    $balance = CoreLeaveBalanceModal::where([
      'user_id' => $this->user_id,
      'leave_type_id' => $this->leave_type_id,
      'year' => $this->start_date->year
    ])->first();

    if ($balance) {
      $balance->removePendingDays($this->total_days);
    }

    return $this->delete();
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      // Calculate total days if not provided
      if (!$model->total_days) {
        $model->total_days = $model->start_date->diffInDays($model->end_date) + 1;
      }

      // Update leave balance
      if ($model->status === 'pending') {
        $balance = CoreLeaveBalanceModal::firstOrCreate([
          'user_id' => $model->user_id,
          'leave_type_id' => $model->leave_type_id,
          'year' => $model->start_date->year
        ]);

        if (!$balance->canRequestLeave($model->total_days, $model->start_date)) {
          return false;
        }

        $balance->addPendingDays($model->total_days);
      }
    });

    static::deleted(function ($model) {
      // Update leave balance when request is deleted
      if ($model->status === 'pending') {
        $balance = CoreLeaveBalanceModal::where([
          'user_id' => $model->user_id,
          'leave_type_id' => $model->leave_type_id,
          'year' => $model->start_date->year
        ])->first();

        if ($balance) {
          $balance->removePendingDays($model->total_days);
        }
      }
    });
  }
}
