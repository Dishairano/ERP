<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreEmployeeAvailabilityModal extends Model
{
  protected $table = 'employee_availability';

  protected $fillable = [
    'user_id',
    'date',
    'start_time',
    'end_time',
    'availability_type',
    'notes'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime'
  ];

  // Relationships
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
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

  public function scopeAvailable($query)
  {
    return $query->where('availability_type', 'available');
  }

  public function scopeUnavailable($query)
  {
    return $query->where('availability_type', 'unavailable');
  }

  public function scopePreferred($query)
  {
    return $query->where('availability_type', 'preferred');
  }

  // Accessors
  public function getTimeRangeAttribute(): string
  {
    return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
  }

  public function getAvailabilityBadgeAttribute(): string
  {
    return match ($this->availability_type) {
      'available' => '<span class="badge badge-success">Available</span>',
      'unavailable' => '<span class="badge badge-danger">Unavailable</span>',
      'preferred' => '<span class="badge badge-primary">Preferred</span>',
      default => '<span class="badge badge-secondary">Unknown</span>'
    };
  }

  // Methods
  public function isAvailableForShift(CoreWorkShiftModal $shift, string $date): bool
  {
    // If marked as unavailable, employee is not available
    if ($this->availability_type === 'unavailable') {
      return false;
    }

    // Convert times to comparable format
    $shiftStart = strtotime($date . ' ' . $shift->start_time->format('H:i:s'));
    $shiftEnd = strtotime($date . ' ' . $shift->end_time->format('H:i:s'));
    $availStart = strtotime($this->start_time->format('Y-m-d H:i:s'));
    $availEnd = strtotime($this->end_time->format('Y-m-d H:i:s'));

    // Check if shift time falls within availability time
    return ($shiftStart >= $availStart && $shiftEnd <= $availEnd);
  }

  public function overlapsWithShift(CoreWorkShiftModal $shift, string $date): bool
  {
    $shiftStart = strtotime($date . ' ' . $shift->start_time->format('H:i:s'));
    $shiftEnd = strtotime($date . ' ' . $shift->end_time->format('H:i:s'));
    $availStart = strtotime($this->start_time->format('Y-m-d H:i:s'));
    $availEnd = strtotime($this->end_time->format('Y-m-d H:i:s'));

    return ($shiftStart < $availEnd && $shiftEnd > $availStart);
  }

  public function getDurationInHours(): float
  {
    $start = new \DateTime($this->start_time);
    $end = new \DateTime($this->end_time);
    $interval = $start->diff($end);

    return $interval->h + ($interval->i / 60);
  }

  public function updateTimeRange(string $startTime, string $endTime): bool
  {
    // Validate time format and range
    $start = strtotime($startTime);
    $end = strtotime($endTime);

    if (!$start || !$end || $start >= $end) {
      return false;
    }

    return $this->update([
      'start_time' => $startTime,
      'end_time' => $endTime
    ]);
  }

  public function setAvailabilityType(string $type): bool
  {
    if (!in_array($type, ['available', 'unavailable', 'preferred'])) {
      return false;
    }

    return $this->update(['availability_type' => $type]);
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      // Validate time range
      $start = strtotime($model->start_time);
      $end = strtotime($model->end_time);

      if (!$start || !$end || $start >= $end) {
        return false;
      }

      // Validate availability type
      if (!in_array($model->availability_type, ['available', 'unavailable', 'preferred'])) {
        return false;
      }

      // Check for overlapping availability records
      $overlapping = self::where('user_id', $model->user_id)
        ->where('date', $model->date)
        ->where('id', '!=', $model->id)
        ->where(function ($query) use ($start, $end) {
          $query->where(function ($q) use ($start, $end) {
            $q->where('start_time', '<=', date('Y-m-d H:i:s', $start))
              ->where('end_time', '>', date('Y-m-d H:i:s', $start));
          })->orWhere(function ($q) use ($start, $end) {
            $q->where('start_time', '<', date('Y-m-d H:i:s', $end))
              ->where('end_time', '>=', date('Y-m-d H:i:s', $end));
          });
        })
        ->exists();

      return !$overlapping;
    });
  }
}
