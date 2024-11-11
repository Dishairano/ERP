<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
  protected $fillable = [
    'project_id',
    'name',
    'description',
    'status',
    'start_date',
    'due_date',
    'estimated_hours',
    'assigned_to'
  ];

  protected $casts = [
    'start_date' => 'date',
    'due_date' => 'date',
    'estimated_hours' => 'integer'
  ];

  /**
   * Get the project that owns the task.
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the user assigned to the task.
   */
  public function assignedUser(): BelongsTo
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  /**
   * Get the time registrations for the task.
   */
  public function timeRegistrations(): HasMany
  {
    return $this->hasMany(TimeRegistration::class);
  }

  /**
   * Get the total hours logged for this task.
   */
  public function getTotalHoursAttribute(): float
  {
    return $this->timeRegistrations()
      ->where('status', 'approved')
      ->sum('hours');
  }

  /**
   * Get the completion percentage based on logged hours vs estimated hours.
   */
  public function getCompletionPercentageAttribute(): float
  {
    if (!$this->estimated_hours) {
      return 0;
    }

    return min(100, ($this->total_hours / $this->estimated_hours) * 100);
  }

  /**
   * Scope a query to only include active tasks.
   */
  public function scopeActive($query)
  {
    return $query->whereIn('status', ['pending', 'in_progress']);
  }

  /**
   * Scope a query to only include tasks assigned to a specific user.
   */
  public function scopeAssignedTo($query, $userId)
  {
    return $query->where('assigned_to', $userId);
  }

  /**
   * Scope a query to only include overdue tasks.
   */
  public function scopeOverdue($query)
  {
    return $query->where('due_date', '<', now())
      ->whereNotIn('status', ['completed']);
  }
}
