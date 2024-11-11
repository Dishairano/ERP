<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectSettingModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'project_settings';

  protected $fillable = [
    'project_id',
    'notifications_enabled',
    'reminder_frequency',
    'visibility',
    'task_categories',
    'custom_fields',
    'default_assignee_id',
    'default_priority',
    'working_days',
    'working_hours',
    'currency',
    'date_format',
    'time_format'
  ];

  protected $casts = [
    'notifications_enabled' => 'boolean',
    'task_categories' => 'array',
    'custom_fields' => 'array',
    'working_days' => 'array',
    'working_hours' => 'array'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  public function defaultAssignee()
  {
    return $this->belongsTo(User::class, 'default_assignee_id');
  }

  public function getWorkingDaysTextAttribute()
  {
    $days = [
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday',
      6 => 'Saturday',
      7 => 'Sunday'
    ];

    return collect($this->working_days)->map(function ($day) use ($days) {
      return $days[$day];
    })->implode(', ');
  }

  public function getWorkingHoursTextAttribute()
  {
    return sprintf(
      '%s - %s',
      date('H:i', strtotime($this->working_hours['start'])),
      date('H:i', strtotime($this->working_hours['end']))
    );
  }

  public function getIsWorkingDayAttribute()
  {
    return in_array(now()->dayOfWeek, $this->working_days);
  }

  public function getIsWorkingHoursAttribute()
  {
    $now = now();
    $start = strtotime($this->working_hours['start']);
    $end = strtotime($this->working_hours['end']);
    $current = strtotime($now->format('H:i'));

    return $current >= $start && $current <= $end;
  }

  public function getFormattedDateAttribute($date)
  {
    return $date ? date($this->date_format, strtotime($date)) : null;
  }

  public function getFormattedTimeAttribute($time)
  {
    return $time ? date($this->time_format, strtotime($time)) : null;
  }

  public function getFormattedCurrencyAttribute($amount)
  {
    return number_format($amount, 2) . ' ' . $this->currency;
  }
}
