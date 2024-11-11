<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectTaskModal extends Model
{
  use SoftDeletes;

  protected $table = 'project_tasks';

  protected $fillable = [
    'project_id',
    'title',
    'description',
    'status',
    'priority',
    'assigned_to',
    'start_date',
    'due_date',
    'completed_at',
    'estimated_hours',
    'actual_hours',
    'actual_cost',
    'attachments',
    'comments',
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'due_date' => 'datetime',
    'completed_at' => 'datetime',
    'attachments' => 'array',
    'comments' => 'array',
  ];

  // Relationships
  public function project()
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  public function assignedTo()
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  public function createdBy()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  // Accessors
  public function getProgressAttribute()
  {
    if ($this->status === 'completed') {
      return 100;
    }

    if ($this->status === 'in_progress') {
      if ($this->estimated_hours && $this->actual_hours) {
        return min(round(($this->actual_hours / $this->estimated_hours) * 100), 99);
      }
      return 50;
    }

    return 0;
  }
}
