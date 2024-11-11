<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmAttendanceModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_attendances';

  protected $fillable = [
    'employee_id',
    'date',
    'check_in',
    'check_out',
    'break_start',
    'break_end',
    'total_hours',
    'overtime_hours',
    'late_minutes',
    'early_departure_minutes',
    'break_duration',
    'status', // present, absent, half-day, on-leave, work-from-home
    'shift_type', // morning, evening, night, flexible
    'location', // office, remote, client-site
    'ip_address',
    'device_info',
    'geo_location',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'date' => 'date',
    'check_in' => 'datetime',
    'check_out' => 'datetime',
    'break_start' => 'datetime',
    'break_end' => 'datetime',
    'total_hours' => 'decimal:2',
    'overtime_hours' => 'decimal:2',
    'late_minutes' => 'integer',
    'early_departure_minutes' => 'integer',
    'break_duration' => 'integer',
    'geo_location' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the attendance record.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available attendance statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'present',
      'absent',
      'half-day',
      'on-leave',
      'work-from-home'
    ];
  }

  /**
   * Get all available shift types.
   */
  public static function getShiftTypes(): array
  {
    return [
      'morning',
      'evening',
      'night',
      'flexible'
    ];
  }

  /**
   * Get all available locations.
   */
  public static function getLocations(): array
  {
    return [
      'office',
      'remote',
      'client-site'
    ];
  }

  /**
   * Calculate total working hours.
   */
  public function calculateTotalHours(): float
  {
    if (!$this->check_in || !$this->check_out) {
      return 0;
    }

    $totalMinutes = $this->check_out->diffInMinutes($this->check_in);

    if ($this->break_start && $this->break_end) {
      $breakMinutes = $this->break_end->diffInMinutes($this->break_start);
      $totalMinutes -= $breakMinutes;
    }

    return round($totalMinutes / 60, 2);
  }

  /**
   * Check if the employee is currently checked in.
   */
  public function isCheckedIn(): bool
  {
    return $this->check_in && !$this->check_out;
  }

  /**
   * Check if the employee is on break.
   */
  public function isOnBreak(): bool
  {
    return $this->break_start && !$this->break_end;
  }

  /**
   * Check if the employee was late.
   */
  public function wasLate(): bool
  {
    return $this->late_minutes > 0;
  }

  /**
   * Check if the employee left early.
   */
  public function leftEarly(): bool
  {
    return $this->early_departure_minutes > 0;
  }

  /**
   * Check if the employee did overtime.
   */
  public function didOvertime(): bool
  {
    return $this->overtime_hours > 0;
  }

  /**
   * Get the attendance status with color code.
   */
  public function getStatusWithColor(): array
  {
    $colors = [
      'present' => 'green',
      'absent' => 'red',
      'half-day' => 'orange',
      'on-leave' => 'blue',
      'work-from-home' => 'purple'
    ];

    return [
      'status' => $this->status,
      'color' => $colors[$this->status] ?? 'gray'
    ];
  }

  /**
   * Scope a query to only include attendance records for today.
   */
  public function scopeToday($query)
  {
    return $query->whereDate('date', today());
  }

  /**
   * Scope a query to only include attendance records for a specific date.
   */
  public function scopeForDate($query, $date)
  {
    return $query->whereDate('date', $date);
  }

  /**
   * Scope a query to only include attendance records within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include attendance records with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include attendance records for a specific shift.
   */
  public function scopeForShift($query, $shiftType)
  {
    return $query->where('shift_type', $shiftType);
  }

  /**
   * Scope a query to only include attendance records at a specific location.
   */
  public function scopeAtLocation($query, $location)
  {
    return $query->where('location', $location);
  }

  /**
   * Scope a query to only include late attendance records.
   */
  public function scopeLate($query)
  {
    return $query->where('late_minutes', '>', 0);
  }

  /**
   * Scope a query to only include early departure records.
   */
  public function scopeEarlyDeparture($query)
  {
    return $query->where('early_departure_minutes', '>', 0);
  }

  /**
   * Scope a query to only include overtime records.
   */
  public function scopeWithOvertime($query)
  {
    return $query->where('overtime_hours', '>', 0);
  }

  /**
   * Scope a query to only include records with breaks.
   */
  public function scopeWithBreaks($query)
  {
    return $query->whereNotNull('break_start')
      ->whereNotNull('break_end');
  }
}
