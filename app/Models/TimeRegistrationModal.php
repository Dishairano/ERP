<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeRegistrationModal extends Model
{
  use HasFactory;

  protected $table = 'time_registrations';

  protected $fillable = [
    'user_id',
    'project_id',
    'task_id',
    'date',
    'hours',
    'description',
    'billable',
    'overtime',
    'status',
    'rejection_reason'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'float',
    'billable' => 'boolean',
    'overtime' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user that owns the time registration.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get the project that owns the time registration.
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(CoreProjectDashboardModal::class, 'project_id');
  }

  /**
   * Get the task that owns the time registration.
   */
  public function task(): BelongsTo
  {
    return $this->belongsTo(CoreProjectTaskModal::class, 'task_id');
  }

  /**
   * Get the total hours for a given date range and user.
   */
  public static function getTotalHours($startDate, $endDate, $userId = null)
  {
    $query = self::whereBetween('date', [$startDate, $endDate]);

    if ($userId) {
      $query->where('user_id', $userId);
    }

    return $query->sum('hours');
  }

  /**
   * Get the billable hours for a given date range and user.
   */
  public static function getBillableHours($startDate, $endDate, $userId = null)
  {
    $query = self::whereBetween('date', [$startDate, $endDate])
      ->where('billable', true);

    if ($userId) {
      $query->where('user_id', $userId);
    }

    return $query->sum('hours');
  }

  /**
   * Get the overtime hours for a given date range and user.
   */
  public static function getOvertimeHours($startDate, $endDate, $userId = null)
  {
    $query = self::whereBetween('date', [$startDate, $endDate])
      ->where('overtime', true);

    if ($userId) {
      $query->where('user_id', $userId);
    }

    return $query->sum('hours');
  }

  /**
   * Get registrations grouped by project for a given date range and user.
   */
  public static function getHoursByProject($startDate, $endDate, $userId = null)
  {
    $query = self::whereBetween('date', [$startDate, $endDate])
      ->with('project');

    if ($userId) {
      $query->where('user_id', $userId);
    }

    return $query->get()
      ->groupBy('project_id')
      ->map(function ($group) {
        return [
          'project' => $group->first()->project,
          'total_hours' => $group->sum('hours'),
          'billable_hours' => $group->where('billable', true)->sum('hours'),
          'overtime_hours' => $group->where('overtime', true)->sum('hours')
        ];
      });
  }

  /**
   * Get registrations grouped by status for a given date range and user.
   */
  public static function getHoursByStatus($startDate, $endDate, $userId = null)
  {
    $query = self::whereBetween('date', [$startDate, $endDate]);

    if ($userId) {
      $query->where('user_id', $userId);
    }

    return $query->get()
      ->groupBy('status')
      ->map(function ($group) {
        return $group->sum('hours');
      });
  }

  /**
   * Scope a query to only include registrations with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include billable registrations.
   */
  public function scopeBillable($query)
  {
    return $query->where('billable', true);
  }

  /**
   * Scope a query to only include overtime registrations.
   */
  public function scopeOvertime($query)
  {
    return $query->where('overtime', true);
  }
}
