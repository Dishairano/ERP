<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeBudget extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'project_id',
    'total_hours',
    'start_date',
    'end_date',
    'task_budgets',
    'status',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'total_hours' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date',
    'task_budgets' => 'array'
  ];

  /**
   * Get the project that owns the budget.
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the tasks associated with this budget.
   */
  public function tasks()
  {
    return $this->belongsToMany(ProjectTask::class, 'time_budget_tasks')
      ->withPivot('hours');
  }

  /**
   * Get the user who created the budget.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the budget's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the budget's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
