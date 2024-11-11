<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
  use HasFactory;

  protected $fillable = [
    'project_id',
    'name',
    'description',
    'start_date',
    'end_date',
    'order',
    'status'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
  ];

  // Relationships
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function tasks()
  {
    return $this->hasMany(ProjectTask::class, 'phase_id');
  }

  // Helper methods
  public function getProgress()
  {
    $totalTasks = $this->tasks()->count();
    if ($totalTasks === 0) return 0;

    $completedTasks = $this->tasks()->where('status', 'completed')->count();
    return ($completedTasks / $totalTasks) * 100;
  }

  public function isOverdue()
  {
    return $this->end_date < now() && $this->status !== 'completed';
  }

  // Scopes
  public function scopeInProgress($query)
  {
    return $query->where('status', 'in_progress');
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('order');
  }
}
