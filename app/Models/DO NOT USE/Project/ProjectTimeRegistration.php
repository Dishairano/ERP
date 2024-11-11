<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class ProjectTimeRegistration extends Model
{
  use HasFactory;

  protected $table = 'project_time_registrations';

  protected $fillable = [
    'project_id',
    'task_id',
    'user_id',
    'hours',
    'date',
    'description',
    'status',
    'approved_by',
    'approved_at',
    'rejected_reason'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'float',
    'approved_at' => 'datetime'
  ];

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function task()
  {
    return $this->belongsTo(ProjectTask::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }
}
