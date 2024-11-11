<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoreWorkShiftModal extends Model
{
  protected $table = 'work_shifts';

  protected $fillable = [
    'name',
    'start_time',
    'end_time',
    'hours',
    'break_times',
    'is_night_shift'
  ];

  protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'hours' => 'decimal:2',
    'break_times' => 'array',
    'is_night_shift' => 'boolean'
  ];

  // Relationships
  public function schedules(): HasMany
  {
    return $this->hasMany(CoreEmployeeScheduleModal::class, 'shift_id');
  }

  // Scopes
  public function scopeNightShift($query)
  {
    return $query->where('is_night_shift', true);
  }

  public function scopeDayShift($query)
  {
    return $query->where('is_night_shift', false);
  }

  // Accessors
  public function getShiftTimesAttribute(): string
  {
    return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
  }

  public function getTotalBreakTimeAttribute(): int
  {
    if (!$this->break_times) {
      return 0;
    }

    return collect($this->break_times)->sum(function ($break) {
      return $this->calculateBreakDuration($break['start'], $break['end']);
    });
  }

  public function getNetWorkingHoursAttribute(): float
  {
    return $this->hours - ($this->total_break_time / 60);
  }

  // Methods
  public function addBreakTime(string $start, string $end): bool
  {
    $breakTimes = $this->break_times ?? [];
    $breakTimes[] = [
      'start' => $start,
      'end' => $end
    ];

    return $this->update(['break_times' => $breakTimes]);
  }

  public function removeBreakTime(int $index): bool
  {
    if (!isset($this->break_times[$index])) {
      return false;
    }

    $breakTimes = $this->break_times;
    unset($breakTimes[$index]);

    return $this->update(['break_times' => array_values($breakTimes)]);
  }

  public function updateBreakTime(int $index, string $start, string $end): bool
  {
    if (!isset($this->break_times[$index])) {
      return false;
    }

    $breakTimes = $this->break_times;
    $breakTimes[$index] = [
      'start' => $start,
      'end' => $end
    ];

    return $this->update(['break_times' => $breakTimes]);
  }

  public function isOverlapping(string $startTime, string $endTime): bool
  {
    $start = strtotime($startTime);
    $end = strtotime($endTime);
    $shiftStart = strtotime($this->start_time->format('H:i'));
    $shiftEnd = strtotime($this->end_time->format('H:i'));

    return ($start < $shiftEnd && $end > $shiftStart);
  }

  public function calculateOvertime(\DateTime $actualEndTime): float
  {
    if ($actualEndTime <= $this->end_time) {
      return 0;
    }

    $overtime = $actualEndTime->diff($this->end_time);
    return round($overtime->h + ($overtime->i / 60), 2);
  }

  protected function calculateBreakDuration(string $start, string $end): int
  {
    $startTime = strtotime($start);
    $endTime = strtotime($end);
    return ($endTime - $startTime) / 60; // Duration in minutes
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

      // Validate break times
      if ($model->break_times) {
        foreach ($model->break_times as $break) {
          if (!isset($break['start'], $break['end'])) {
            return false;
          }

          $breakStart = strtotime($break['start']);
          $breakEnd = strtotime($break['end']);

          if ($breakStart >= $breakEnd) {
            return false;
          }
        }
      }
    });
  }
}
