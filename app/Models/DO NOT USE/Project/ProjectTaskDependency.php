<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectTaskDependency extends Model
{
  use HasFactory;

  protected $table = 'project_task_dependencies';

  protected $fillable = [
    'task_id',
    'dependent_task_id',
    'dependency_type',
    'lag_time'
  ];

  protected $casts = [
    'lag_time' => 'integer'
  ];

  public function task()
  {
    return $this->belongsTo(ProjectTask::class, 'task_id');
  }

  public function dependentTask()
  {
    return $this->belongsTo(ProjectTask::class, 'dependent_task_id');
  }
}
