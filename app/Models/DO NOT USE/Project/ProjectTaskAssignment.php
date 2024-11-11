<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectTaskAssignment extends Model
{
  use HasFactory;

  protected $table = 'project_task_assignments';

  protected $fillable = [
    'task_id',
    'user_id',
    'allocated_hours',
    'start_date',
    'end_date',
    'status'
  ];

  protected $casts = [
    'allocated_hours' => 'float',
    'start_date' => 'datetime',
    'end_date' => 'datetime'
  ];

  public function task()
  {
    return $this->belongsTo(ProjectTask::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
