<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
  protected $fillable = [
    'project_id',
    'title',
    'description',
    'due_date',
    'completion_date',
    'status',
    'priority',
    'deliverables',
    'dependencies'
  ];

  protected $casts = [
    'due_date' => 'date',
    'completion_date' => 'date',
    'deliverables' => 'array',
    'dependencies' => 'array'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectDashboardModal::class, 'project_id');
  }

  public function tasks()
  {
    return $this->hasMany(Task::class);
  }

  public function isCompleted()
  {
    return $this->status === 'completed';
  }

  public function isOverdue()
  {
    return !$this->isCompleted() && $this->due_date < now();
  }
}
