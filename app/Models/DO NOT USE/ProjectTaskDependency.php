<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTaskDependency extends Model
{
  use HasFactory;

  protected $fillable = [
    'task_id',
    'dependent_task_id',
    'dependency_type',
    'lag_days'
  ];

  protected $casts = [
    'lag_days' => 'integer'
  ];

  public function task()
  {
    return $this->belongsTo(ProjectTask::class, 'task_id');
  }

  public function dependentTask()
  {
    return $this->belongsTo(ProjectTask::class, 'dependent_task_id');
  }

  public function isBlocking()
  {
    return $this->dependentTask->status !== 'completed';
  }

  public function getEarliestStartDate()
  {
    $dependentTask = $this->dependentTask;

    switch ($this->dependency_type) {
      case 'finish_to_start':
        return $dependentTask->end_date->addDays($this->lag_days);
      case 'start_to_start':
        return $dependentTask->start_date->addDays($this->lag_days);
      case 'finish_to_finish':
        return $dependentTask->end_date->subDays($this->task->estimated_duration)->addDays($this->lag_days);
      case 'start_to_finish':
        return $dependentTask->start_date->addDays($this->lag_days);
      default:
        return null;
    }
  }
}
