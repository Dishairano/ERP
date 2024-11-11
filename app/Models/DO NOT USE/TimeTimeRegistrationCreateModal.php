<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeTimeRegistrationCreateModal extends Model
{
  protected $table = 'time_registrations';

  protected $fillable = [
    'user_id',
    'project_id',
    'task_id',
    'date',
    'start_time',
    'end_time',
    'duration',
    'description',
    'status',
    'billable',
    'cost_rate',
    'bill_rate'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'duration' => 'decimal:2',
    'billable' => 'boolean',
    'cost_rate' => 'decimal:2',
    'bill_rate' => 'decimal:2'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function task()
  {
    return $this->belongsTo(Task::class);
  }

  public static function calculateDuration($startTime, $endTime)
  {
    return Carbon::parse($startTime)->floatDiffInHours(Carbon::parse($endTime));
  }

  public static function validateTimeOverlap($userId, $date, $startTime, $endTime, $excludeId = null)
  {
    $query = self::where('user_id', $userId)
      ->where('date', $date)
      ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($q) use ($startTime, $endTime) {
          $q->where('start_time', '<=', $startTime)
            ->where('end_time', '>', $startTime);
        })->orWhere(function ($q) use ($startTime, $endTime) {
          $q->where('start_time', '<', $endTime)
            ->where('end_time', '>=', $endTime);
        })->orWhere(function ($q) use ($startTime, $endTime) {
          $q->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime);
        });
      });

    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }

    return $query->exists();
  }

  public static function getDefaultRates($projectId, $userId)
  {
    $project = Project::find($projectId);
    $user = User::find($userId);

    return [
      'cost_rate' => $user->cost_rate ?? 0,
      'bill_rate' => $project->bill_rate ?? 0,
      'billable' => $project->is_billable ?? false
    ];
  }

  public function setStartTimeAttribute($value)
  {
    $this->attributes['start_time'] = Carbon::parse($this->date . ' ' . $value);
  }

  public function setEndTimeAttribute($value)
  {
    $this->attributes['end_time'] = Carbon::parse($this->date . ' ' . $value);
  }

  public function getFormattedStartTimeAttribute()
  {
    return $this->start_time ? Carbon::parse($this->start_time)->format('H:i') : '';
  }

  public function getFormattedEndTimeAttribute()
  {
    return $this->end_time ? Carbon::parse($this->end_time)->format('H:i') : '';
  }

  public function getFormattedDurationAttribute()
  {
    $hours = floor($this->duration);
    $minutes = round(($this->duration - $hours) * 60);
    return sprintf('%02d:%02d', $hours, $minutes);
  }
}
