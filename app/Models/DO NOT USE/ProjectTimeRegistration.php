<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimeRegistration extends Model
{
  use HasFactory;

  protected $fillable = [
    'task_id',
    'user_id',
    'date',
    'hours',
    'description',
    'status'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'integer'
  ];

  public function task()
  {
    return $this->belongsTo(ProjectTask::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function approve()
  {
    $this->status = 'approved';
    $this->save();

    // Update task actual hours
    $this->task->actual_hours = ProjectTimeRegistration::where('task_id', $this->task_id)
      ->where('status', 'approved')
      ->sum('hours');
    $this->task->save();

    // Update task progress
    $this->task->updateProgress();
  }

  public function reject()
  {
    $this->status = 'rejected';
    $this->save();
  }
}
