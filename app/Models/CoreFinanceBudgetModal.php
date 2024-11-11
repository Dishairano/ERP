<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceBudgetModal extends Model
{
  use HasFactory;

  protected $table = 'finance_budgets';

  protected $fillable = [
    'name',
    'description',
    'fiscal_year',
    'start_date',
    'end_date',
    'total_amount',
    'allocated_amount',
    'remaining_amount',
    'status',
    'department_id',
    'project_id',
    'created_by',
    'approved_by',
    'approved_at',
    'notes'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'total_amount' => 'decimal:2',
    'allocated_amount' => 'decimal:2',
    'remaining_amount' => 'decimal:2',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the budget.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the budget.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the department that owns the budget.
   */
  public function department(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceDepartmentModal::class, 'department_id');
  }

  /**
   * Get the project that owns the budget.
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(CoreProjectDashboardModal::class, 'project_id');
  }

  /**
   * Get the line items for the budget.
   */
  public function lineItems(): HasMany
  {
    return $this->hasMany(CoreFinanceBudgetLineItemModal::class, 'budget_id');
  }

  /**
   * Get the scenarios for the budget.
   */
  public function scenarios(): HasMany
  {
    return $this->hasMany(CoreFinanceBudgetScenarioModal::class, 'budget_id');
  }

  /**
   * Check if the budget is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Check if the budget is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Check if the budget is closed.
   */
  public function isClosed(): bool
  {
    return $this->status === 'closed';
  }

  /**
   * Check if the budget is over allocated.
   */
  public function isOverAllocated(): bool
  {
    return $this->allocated_amount > $this->total_amount;
  }

  /**
   * Get the budget utilization percentage.
   */
  public function getUtilizationPercentage(): float
  {
    if ($this->total_amount <= 0) {
      return 0;
    }

    return ($this->allocated_amount / $this->total_amount) * 100;
  }

  /**
   * Format the total amount as currency.
   */
  public function getFormattedTotalAmount(): string
  {
    return number_format($this->total_amount, 2);
  }

  /**
   * Format the allocated amount as currency.
   */
  public function getFormattedAllocatedAmount(): string
  {
    return number_format($this->allocated_amount, 2);
  }

  /**
   * Format the remaining amount as currency.
   */
  public function getFormattedRemainingAmount(): string
  {
    return number_format($this->remaining_amount, 2);
  }

  /**
   * Scope a query to only include budgets for a specific fiscal year.
   */
  public function scopeForFiscalYear($query, $year)
  {
    return $query->where('fiscal_year', $year);
  }

  /**
   * Scope a query to only include active budgets.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include department budgets.
   */
  public function scopeDepartmentBudgets($query)
  {
    return $query->whereNotNull('department_id');
  }

  /**
   * Scope a query to only include project budgets.
   */
  public function scopeProjectBudgets($query)
  {
    return $query->whereNotNull('project_id');
  }
}
