<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CoreScheduleTemplateModal extends Model
{
  protected $table = 'schedule_templates';

  protected $fillable = [
    'name',
    'description',
    'pattern'
  ];

  protected $casts = [
    'pattern' => 'array'
  ];

  // Methods
  public function generateSchedules(array $userIds, Carbon $startDate, Carbon $endDate): bool
  {
    // Validate inputs
    if (empty($userIds) || $startDate >= $endDate) {
      return false;
    }

    // Get pattern length in days
    $patternLength = count($this->pattern);
    if ($patternLength === 0) {
      return false;
    }

    // Calculate number of days to generate schedules for
    $days = $startDate->diffInDays($endDate) + 1;

    // Begin transaction
    return DB::transaction(function () use ($userIds, $startDate, $days, $patternLength) {
      $success = true;
      $currentDate = $startDate->copy();

      // For each day in the range
      for ($day = 0; $day < $days; $day++) {
        // Get pattern index for current day
        $patternIndex = $day % $patternLength;
        $dayPattern = $this->pattern[$patternIndex];

        // Skip if no shift assigned for this day
        if (empty($dayPattern['shift_id'])) {
          $currentDate->addDay();
          continue;
        }

        // Create schedule for each user
        foreach ($userIds as $userId) {
          // Check if schedule already exists
          $exists = CoreEmployeeScheduleModal::where('user_id', $userId)
            ->where('date', $currentDate->format('Y-m-d'))
            ->exists();

          if (!$exists) {
            $schedule = new CoreEmployeeScheduleModal([
              'user_id' => $userId,
              'shift_id' => $dayPattern['shift_id'],
              'date' => $currentDate->format('Y-m-d'),
              'status' => 'scheduled'
            ]);

            if (!$schedule->save()) {
              $success = false;
              break 2; // Break both loops
            }
          }
        }

        $currentDate->addDay();
      }

      return $success;
    });
  }

  public function validatePattern(): bool
  {
    if (!is_array($this->pattern)) {
      return false;
    }

    foreach ($this->pattern as $day) {
      // Each day must have at least shift_id defined
      if (!isset($day['shift_id'])) {
        return false;
      }

      // Validate shift exists
      if ($day['shift_id'] && !CoreWorkShiftModal::find($day['shift_id'])) {
        return false;
      }
    }

    return true;
  }

  public function getPatternPreview(): array
  {
    $preview = [];
    foreach ($this->pattern as $index => $day) {
      if (empty($day['shift_id'])) {
        $preview[] = [
          'day' => $index + 1,
          'shift' => 'Off',
          'hours' => 0
        ];
        continue;
      }

      $shift = CoreWorkShiftModal::find($day['shift_id']);
      if ($shift) {
        $preview[] = [
          'day' => $index + 1,
          'shift' => $shift->name,
          'hours' => $shift->hours
        ];
      }
    }

    return $preview;
  }

  public function getTotalHoursPerCycle(): float
  {
    return collect($this->pattern)->sum(function ($day) {
      if (empty($day['shift_id'])) {
        return 0;
      }

      $shift = CoreWorkShiftModal::find($day['shift_id']);
      return $shift ? $shift->hours : 0;
    });
  }

  public function getAverageHoursPerWeek(): float
  {
    $totalHours = $this->getTotalHoursPerCycle();
    $daysInCycle = count($this->pattern);
    return round(($totalHours / $daysInCycle) * 7, 2);
  }

  public function getDaysOffPerCycle(): int
  {
    return collect($this->pattern)->filter(function ($day) {
      return empty($day['shift_id']);
    })->count();
  }

  public function getConsecutiveWorkDays(): array
  {
    $consecutive = 0;
    $maxConsecutive = 0;
    $currentStreak = 0;

    foreach ($this->pattern as $day) {
      if (!empty($day['shift_id'])) {
        $currentStreak++;
        $maxConsecutive = max($maxConsecutive, $currentStreak);
      } else {
        $currentStreak = 0;
      }
    }

    // Check if pattern wraps around
    if ($currentStreak > 0) {
      foreach ($this->pattern as $day) {
        if (!empty($day['shift_id'])) {
          $currentStreak++;
          $maxConsecutive = max($maxConsecutive, $currentStreak);
        } else {
          break;
        }
      }
    }

    return [
      'current' => $currentStreak,
      'maximum' => $maxConsecutive
    ];
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      return $model->validatePattern();
    });
  }
}
