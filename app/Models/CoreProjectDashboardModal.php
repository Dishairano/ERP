<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreProjectDashboardModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'project_dashboards';

  protected $fillable = [
    'project_id',
    'total_tasks',
    'completed_tasks',
    'pending_tasks',
    'overdue_tasks',
    'progress_percentage',
    'budget_allocated',
    'budget_spent',
    'budget_remaining',
    'start_date',
    'end_date',
    'status',
    'priority',
    'team_members',
    'recent_activities',
    'upcoming_milestones',
    'risk_summary'
  ];

  protected $casts = [
    'total_tasks' => 'integer',
    'completed_tasks' => 'integer',
    'pending_tasks' => 'integer',
    'overdue_tasks' => 'integer',
    'progress_percentage' => 'decimal:2',
    'budget_allocated' => 'decimal:2',
    'budget_spent' => 'decimal:2',
    'budget_remaining' => 'decimal:2',
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'team_members' => 'array',
    'recent_activities' => 'array',
    'upcoming_milestones' => 'array',
    'risk_summary' => 'array'
  ];

  public function project()
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  public function tasks()
  {
    return $this->hasMany(CoreProjectTaskModal::class, 'project_id', 'project_id');
  }

  public function risks()
  {
    return $this->hasMany(CoreProjectRiskModal::class, 'project_id', 'project_id');
  }

  public function documents()
  {
    return $this->hasMany(CoreProjectDocumentModal::class, 'project_id', 'project_id');
  }

  public function settings()
  {
    return $this->hasOne(CoreProjectSettingModal::class, 'project_id', 'project_id');
  }

  public function getCompletionPercentageAttribute()
  {
    return $this->total_tasks > 0
      ? round(($this->completed_tasks / $this->total_tasks) * 100, 2)
      : 0;
  }

  public function getBudgetUtilizationAttribute()
  {
    return $this->budget_allocated > 0
      ? round(($this->budget_spent / $this->budget_allocated) * 100, 2)
      : 0;
  }

  public function getDaysRemainingAttribute()
  {
    return now()->diffInDays($this->end_date, false);
  }

  public function getIsOverdueAttribute()
  {
    return $this->end_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
  }

  public function getHighPriorityTasksAttribute()
  {
    return $this->tasks()
      ->where('priority', 'high')
      ->whereNotIn('status', ['completed', 'cancelled'])
      ->count();
  }

  public function getActiveRisksAttribute()
  {
    return $this->risks()
      ->whereNotIn('status', ['mitigated', 'closed'])
      ->count();
  }

  public function getTeamMembersCountAttribute()
  {
    return count($this->team_members ?? []);
  }

  public function getUpcomingMilestonesCountAttribute()
  {
    return count($this->upcoming_milestones ?? []);
  }

  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  public function scopeByPriority($query, $priority)
  {
    return $query->where('priority', $priority);
  }

  public function scopeOverdue($query)
  {
    return $query->where('end_date', '<', now())
      ->whereNotIn('status', ['completed', 'cancelled']);
  }

  public function updateProgress()
  {
    $this->update([
      'total_tasks' => $this->tasks()->count(),
      'completed_tasks' => $this->tasks()->where('status', 'completed')->count(),
      'pending_tasks' => $this->tasks()->whereNotIn('status', ['completed', 'cancelled'])->count(),
      'overdue_tasks' => $this->tasks()->where('due_date', '<', now())->whereNotIn('status', ['completed', 'cancelled'])->count(),
      'progress_percentage' => $this->completion_percentage
    ]);
  }

  public function updateBudget()
  {
    $this->update([
      'budget_spent' => $this->tasks()->sum('actual_cost'),
      'budget_remaining' => $this->budget_allocated - $this->tasks()->sum('actual_cost')
    ]);
  }
}
