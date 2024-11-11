<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimeTracking extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'project_id',
    'task_id',
    'user_id',
    'start_time',
    'end_time',
    'duration',
    'description',
    'billable'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'duration' => 'integer',
    'billable' => 'boolean'
  ];

  /**
   * Get the project that owns the time entry.
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the task that owns the time entry.
   */
  public function task()
  {
    return $this->belongsTo(ProjectTask::class, 'task_id');
  }

  /**
   * Get the user who created the time entry.
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Get all of the time entry's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the time entry's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
