<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreTimeRegistrationModal extends Model
{
  use SoftDeletes;

  protected $table = 'time_registrations';

  protected $fillable = [
    'user_id',
    'project_id',
    'task_id',
    'date',
    'start_time',
    'end_time',
    'hours',
    'description',
    'status',
    'approved_by',
    'approved_at',
    'rejection_reason'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'hours' => 'decimal:2',
    'approved_at' => 'datetime'
  ];

  // Relationships
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function project(): BelongsTo
  {
    return $this->belongsTo(CoreProjectModal::class);
  }

  public function task(): BelongsTo
  {
    return $this->belongsTo(CoreProjectTaskModal::class);
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

  public function scopeForProject($query, $projectId)
  {
    return $query->where('project_id', $projectId);
  }

  public function scopeForTask($query, $taskId)
  {
    return $query->where('task_id', $taskId);
  }

  public function scopeForDate($query, $date)
  {
    return $query->whereDate('date', $date);
  }

  public function scopeForDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  // Accessors & Mutators
  public function getDurationAttribute(): string
  {
    return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
  }

  public function getStatusBadgeAttribute(): string
  {
    return match ($this->status) {
      'approved' => '<span class="badge badge-success">Approved</span>',
      'rejected' => '<span class="badge badge-danger">Rejected</span>',
      default => '<span class="badge badge-warning">Pending</span>'
    };
  }

  // Methods
  public function approve(int $approverId): bool
  {
    return $this->update([
      'status' => 'approved',
      'approved_by' => $approverId,
      'approved_at' => now()
    ]);
  }

  public function reject(int $approverId, string $reason): bool
  {
    return $this->update([
      'status' => 'rejected',
      'approved_by' => $approverId,
      'approved_at' => now(),
      'rejection_reason' => $reason
    ]);
  }

  public function calculateHours(): void
  {
    $start = new \DateTime($this->start_time);
    $end = new \DateTime($this->end_time);
    $interval = $start->diff($end);
    $this->hours = $interval->h + ($interval->i / 60);
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      if (!$model->hours) {
        $model->calculateHours();
      }
    });

    static::updating(function ($model) {
      if ($model->isDirty(['start_time', 'end_time'])) {
        $model->calculateHours();
      }
    });
  }
}
