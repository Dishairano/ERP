<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectTask extends Model
{
  use HasFactory;

  protected $table = 'project_tasks';

  protected $fillable = [
    'name',
    'description',
    'project_id',
    'phase_id',
    'start_date',
    'end_date',
    'estimated_hours',
    'actual_hours',
    'priority',
    'status',
    'progress_percentage'
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'estimated_hours' => 'integer',
    'actual_hours' => 'integer',
    'priority' => 'integer',
    'progress_percentage' => 'integer'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function phase()
  {
    return $this->belongsTo(ProjectPhase::class);
  }

  public function assignments()
  {
    return $this->hasMany(ProjectTaskAssignment::class, 'task_id');
  }

  public function dependencies()
  {
    return $this->hasMany(ProjectTaskDependency::class, 'task_id');
  }

  public function dependentTasks()
  {
    return $this->hasMany(ProjectTaskDependency::class, 'dependent_task_id');
  }

  public function documents()
  {
    return $this->hasMany(ProjectDocument::class, 'task_id');
  }

  public function assignedUsers()
  {
    return $this->belongsToMany(User::class, 'project_task_assignments', 'task_id', 'user_id')
      ->withPivot('allocated_hours')
      ->withTimestamps();
  }
}
