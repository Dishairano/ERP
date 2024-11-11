<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'project_id',
    'phase_id',
    'name',
    'description',
    'start_date',
    'due_date',
    'priority',
    'status',
    'estimated_hours',
    'actual_hours'
  ];

  protected $casts = [
    'start_date' => 'date',
    'due_date' => 'date',
    'estimated_hours' => 'integer',
    'actual_hours' => 'integer'
  ];

  // Relationships
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function phase()
  {
    return $this->belongsTo(ProjectPhase::class, 'phase_id');
  }

  public function assignments()
  {
    return $this->hasMany(ProjectTaskAssignment::class, 'task_id');
  }

  public function assignedUsers()
  {
    return $this->belongsToMany(User::class, 'project_task_assignments', 'task_id', 'user_id')
      ->withPivot('role')
      ->withTimestamps();
  }

  public function dependencies()
  {
    return $this->hasMany(ProjectTaskDependency::class, 'task_id');
  }

  public function dependentTasks()
  {
    return $this->belongsToMany(
      ProjectTask::class,
      'project_task_dependencies',
      'task_id',
      'dependent_task_id'
    )->withPivot('type', 'lag_days')->withTimestamps();
  }

  // Scopes
  public function scopeOverdue($query)
  {
    return $query->where('due_date', '<', now())
      ->where('status', '!=', 'completed');
  }

  public function scopeHighPriority($query)
  {
    return $query->whereIn('priority', ['high', 'critical']);
  }

  public function scopeInProgress($query)
  {
    return $query->where('status', 'in_progress');
  }

  // Helper methods
  public function isOverdue()
  {
    return $this->due_date < now() && $this->status !== 'completed';
  }

  public function getProgress()
  {
    if ($this->status === 'completed') return 100;
    if ($this->status === 'todo') return 0;
    if ($this->status === 'in_progress') return 50;
    if ($this->status === 'review') return 75;
    return 0;
  }

  public function getTimeVariance()
  {
    return $this->actual_hours - $this->estimated_hours;
  }

  public function getResponsibleUser()
  {
    return $this->assignments()
      ->where('role', 'responsible')
      ->first()?->user;
  }

  public function canStart()
  {
    foreach ($this->dependencies as $dependency) {
      $dependentTask = $dependency->dependentTask;

      switch ($dependency->type) {
        case 'finish_to_start':
          if ($dependentTask->status !== 'completed') return false;
          break;
        case 'start_to_start':
          if ($dependentTask->status === 'todo') return false;
          break;
        case 'finish_to_finish':
          if ($dependentTask->status !== 'completed') return false;
          break;
        case 'start_to_finish':
          if ($dependentTask->status === 'todo') return false;
          break;
      }
    }

    return true;
  }
}
