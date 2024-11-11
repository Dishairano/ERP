<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoreEmployeeScheduleModal extends Model
{
  protected $table = 'employee_schedules';

  protected $fillable = [
    'user_id',
    'shift_id',
    'date',
    'actual_start_time',
    'actual_end_time',
    'status',
    'notes'
  ];

  protected $casts = [
    'date' => 'date',
    'actual_start_time' => 'datetime',
    'actual_end_time' => 'datetime'
  ];

  // Relationships
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function shift(): BelongsTo
  {
    return $this->belongsTo(CoreWorkShiftModal::class, 'shift_id');
  }

  public function overtimeRecords(): HasMany
  {
    return $this->hasMany(CoreOvertimeRecordModal::class, 'schedule_id');
  }

  // Scopes
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

  public function scopeScheduled($query)
  {
    return $query->where('status', 'scheduled');
  }

  public function scopeCompleted($query)
  {
    return $query->where('status', 'completed');
  }

  public function scopeAbsent($query)
  {
    return $query->where('status', 'absent');
  }

  // Accessors
  public function getStatusBadgeAttribute(): string
  {
    return match ($this->status) {
      'completed' => '<span class="badge badge-success">Completed</span>',
      'absent' => '<span class="badge badge-danger">Absent</span>',
      default => '<span class="badge badge-info">Scheduled</span>'
    };
  }

  public function getActualHoursWorkedAttribute(): float
  {
    if (!$this->actual_start_time || !$this->actual_end_time) {
      return 0;
    }

    $start = new \DateTime($this->actual_start_time);
    $end = new \DateTime($this->actual_end_time);
    $interval = $start->diff($end);

    return round($interval->h + ($interval->i / 60), 2);
  }

  public function getOvertimeHoursAttribute(): float
  {
    if (!$this->actual_end_time || $this->status !== 'completed') {
      return 0;
    }

    return $this->shift->calculateOvertime(new \DateTime($this->actual_end_time));
  }

  public function getIsLateAttribute(): bool
  {
    if (!$this->actual_start_time || $this->status !== 'completed') {
      return false;
    }

    $scheduledStart = new \DateTime($this->shift->start_time->format('H:i'));
    $actualStart = new \DateTime($this->actual_start_time->format('H:i'));

    return $actualStart > $scheduledStart;
  }

  public function getLateMinutesAttribute(): int
  {
    if (!$this->is_late) {
      return 0;
    }

    $scheduledStart = new \DateTime($this->shift->start_time->format('H:i'));
    $actualStart = new \DateTime($this->actual_start_time->format('H:i'));

    return $scheduledStart->diff($actualStart)->i;
  }

  // Methods
  public function clockIn(): bool
  {
    if ($this->status !== 'scheduled' || $this->actual_start_time) {
      return false;
    }

    return $this->update([
      'actual_start_time' => now(),
      'status' => 'completed'
    ]);
  }

  public function clockOut(): bool
  {
    if ($this->status !== 'completed' || !$this->actual_start_time || $this->actual_end_time) {
      return false;
    }

    $endTime = now();
    $overtime = $this->shift->calculateOvertime($endTime);

    $this->update([
      'actual_end_time' => $endTime
    ]);

    // Create overtime record if applicable
    if ($overtime > 0) {
      $this->overtimeRecords()->create([
        'user_id' => $this->user_id,
        'date' => $this->date,
        'start_time' => $this->shift->end_time,
        'end_time' => $endTime,
        'hours' => $overtime,
        'status' => 'pending'
      ]);
    }

    return true;
  }

  public function markAsAbsent(string $reason = null): bool
  {
    if ($this->status !== 'scheduled') {
      return false;
    }

    return $this->update([
      'status' => 'absent',
      'notes' => $reason
    ]);
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      // Validate schedule doesn't overlap with existing schedules
      $existingSchedule = self::where('user_id', $model->user_id)
        ->where('date', $model->date)
        ->where('id', '!=', $model->id)
        ->first();

      if ($existingSchedule) {
        return false;
      }
    });
  }
}
