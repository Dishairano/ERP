<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectModal extends Model
{
  use SoftDeletes;

  protected $table = 'projects';

  protected $fillable = [
    'name',
    'description',
    'status',
    'priority',
    'start_date',
    'end_date',
    'budget',
    'progress',
    'manager_id',
    'template_id'
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'budget' => 'decimal:2',
    'progress' => 'decimal:2'
  ];

  // Relationships
  public function tasks()
  {
    return $this->hasMany(CoreProjectTaskModal::class, 'project_id');
  }

  public function risks()
  {
    return $this->hasMany(CoreProjectRiskModal::class, 'project_id');
  }

  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  public function template()
  {
    return $this->belongsTo(CoreProjectTemplateModal::class, 'template_id');
  }

  public function team()
  {
    return $this->belongsToMany(User::class, 'project_team', 'core_project_modal_id', 'user_id')
      ->withPivot('role')
      ->withTimestamps();
  }

  // Accessors
  public function getProgressAttribute($value)
  {
    if ($this->tasks()->count() === 0) {
      return 0;
    }

    $totalTasks = $this->tasks()->count();
    $completedTasks = $this->tasks()->where('status', 'completed')->count();
    $inProgressTasks = $this->tasks()->where('status', 'in_progress')->count();

    return round(($completedTasks * 100 + $inProgressTasks * 50) / $totalTasks);
  }

  public function getBudgetSpentAttribute()
  {
    return $this->tasks()->sum('actual_cost') ?? 0;
  }

  public function getBudgetRemainingAttribute()
  {
    return $this->budget - $this->budget_spent;
  }

  public function getRiskSummaryAttribute()
  {
    $risks = $this->risks;
    return [
      'total' => $risks->count(),
      'critical' => $risks->where('severity', '>=', 4)->count(),
      'high' => $risks->whereRaw('severity * likelihood >= ?', [9])->count(),
      'medium' => $risks->whereRaw('severity * likelihood >= ? AND severity * likelihood < ?', [4, 9])->count(),
      'low' => $risks->whereRaw('severity * likelihood < ?', [4])->count(),
    ];
  }

  // Scopes
  public function scopeActive($query)
  {
    return $query->where('status', '!=', 'completed');
  }
}
