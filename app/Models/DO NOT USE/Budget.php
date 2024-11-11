<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'type',
    'department_id',
    'project_id',
    'fiscal_year',
    'start_date',
    'end_date',
    'total_amount',
    'notes',
    'status',
    'rejection_reason',
    'created_by',
    'approved_by',
    'approved_at'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'total_amount' => 'decimal:2',
    'approved_at' => 'datetime'
  ];

  /**
   * Get the department that owns the budget.
   */
  public function department()
  {
    return $this->belongsTo(Department::class);
  }

  /**
   * Get the project that owns the budget.
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the user who created the budget.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the budget.
   */
  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the budget categories.
   */
  public function categories()
  {
    return $this->hasMany(BudgetCategory::class);
  }

  /**
   * Get the budget scenarios.
   */
  public function scenarios()
  {
    return $this->hasMany(BudgetScenario::class);
  }

  /**
   * Get the budget KPIs.
   */
  public function kpis()
  {
    return $this->hasMany(BudgetKpi::class);
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
