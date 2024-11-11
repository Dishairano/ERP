<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
  protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'status',
    'manager_id',
    'budget',
    'client_id'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'budget' => 'decimal:2'
  ];

  /**
   * Get all tasks for the project.
   */
  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  /**
   * Get all time registrations for the project.
   */
  public function timeRegistrations(): HasMany
  {
    return $this->hasMany(TimeRegistration::class);
  }

  /**
   * Get the project manager.
   */
  public function manager(): BelongsTo
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  /**
   * Get the client associated with the project.
   */
  public function client(): BelongsTo
  {
    return $this->belongsTo(User::class, 'client_id');
  }

  /**
   * Get the total hours logged for this project.
   */
  public function getTotalHoursAttribute(): float
  {
    return $this->timeRegistrations()
      ->where('status', 'approved')
      ->sum('hours');
  }

  /**
   * Get the total billable hours logged for this project.
   */
  public function getTotalBillableHoursAttribute(): float
  {
    return $this->timeRegistrations()
      ->where('status', 'approved')
      ->where('billable', true)
      ->sum('hours');
  }

  /**
   * Get the completion percentage based on task completion.
   */
  public function getCompletionPercentageAttribute(): float
  {
    $totalTasks = $this->tasks()->count();
    if (!$totalTasks) {
      return 0;
    }

    $completedTasks = $this->tasks()
      ->where('status', 'completed')
      ->count();

    return ($completedTasks / $totalTasks) * 100;
  }

  /**
   * Get the project's budget utilization percentage.
   */
  public function getBudgetUtilizationAttribute(): float
  {
    if (!$this->budget) {
      return 0;
    }

    $totalBillableAmount = $this->getTotalBillableHoursAttribute() * 100; // Assuming $100/hour rate
    return ($totalBillableAmount / $this->budget) * 100;
  }

  /**
   * Scope a query to only include active projects.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include projects managed by a specific user.
   */
  public function scopeManagedBy($query, $userId)
  {
    return $query->where('manager_id', $userId);
  }

  /**
   * Scope a query to only include overdue projects.
   */
  public function scopeOverdue($query)
  {
    return $query->where('end_date', '<', now())
      ->where('status', '!=', 'completed');
  }

  /**
   * Check if the project is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Check if the project is completed.
   */
  public function isCompleted(): bool
  {
    return $this->status === 'completed';
  }

  /**
   * Check if the project is overdue.
   */
  public function isOverdue(): bool
  {
    return $this->end_date && $this->end_date->isPast() && !$this->isCompleted();
  }
}
