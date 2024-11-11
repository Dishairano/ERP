<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceDepartmentModal extends Model
{
  use HasFactory;

  protected $table = 'finance_departments';

  protected $fillable = [
    'name',
    'code',
    'description',
    'manager_id',
    'parent_id',
    'budget_limit',
    'status',
    'notes'
  ];

  protected $casts = [
    'budget_limit' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the manager of the department.
   */
  public function manager(): BelongsTo
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  /**
   * Get the parent department.
   */
  public function parent(): BelongsTo
  {
    return $this->belongsTo(self::class, 'parent_id');
  }

  /**
   * Get the child departments.
   */
  public function children(): HasMany
  {
    return $this->hasMany(self::class, 'parent_id');
  }

  /**
   * Get the budgets for the department.
   */
  public function budgets(): HasMany
  {
    return $this->hasMany(CoreFinanceBudgetModal::class, 'department_id');
  }

  /**
   * Get the preset budgets for the department.
   */
  public function presetBudgets(): HasMany
  {
    return $this->hasMany(CoreFinancePresetDepartmentModal::class, 'department_id');
  }

  /**
   * Get all active budgets for the department.
   */
  public function activeBudgets(): HasMany
  {
    return $this->budgets()->where('status', 'active');
  }

  /**
   * Calculate total allocated budget.
   */
  public function getTotalAllocatedBudget(): float
  {
    return $this->budgets()->sum('allocated_amount');
  }

  /**
   * Calculate remaining budget limit.
   */
  public function getRemainingBudgetLimit(): float
  {
    return $this->budget_limit - $this->getTotalAllocatedBudget();
  }

  /**
   * Check if department has exceeded budget limit.
   */
  public function hasExceededBudgetLimit(): bool
  {
    return $this->getTotalAllocatedBudget() > $this->budget_limit;
  }

  /**
   * Get budget utilization percentage.
   */
  public function getBudgetUtilizationPercentage(): float
  {
    if ($this->budget_limit <= 0) {
      return 0;
    }

    return ($this->getTotalAllocatedBudget() / $this->budget_limit) * 100;
  }

  /**
   * Get all departments in hierarchical order.
   */
  public static function getHierarchy()
  {
    return self::with('children')
      ->whereNull('parent_id')
      ->get();
  }

  /**
   * Scope a query to only include active departments.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include root departments (no parent).
   */
  public function scopeRoot($query)
  {
    return $query->whereNull('parent_id');
  }

  /**
   * Get the full path of the department (including parent names).
   */
  public function getFullPath(): string
  {
    $path = [$this->name];
    $parent = $this->parent;

    while ($parent) {
      array_unshift($path, $parent->name);
      $parent = $parent->parent;
    }

    return implode(' > ', $path);
  }

  /**
   * Format the budget limit as currency.
   */
  public function getFormattedBudgetLimit(): string
  {
    return number_format($this->budget_limit, 2);
  }

  /**
   * Format the total allocated budget as currency.
   */
  public function getFormattedTotalAllocatedBudget(): string
  {
    return number_format($this->getTotalAllocatedBudget(), 2);
  }

  /**
   * Format the remaining budget limit as currency.
   */
  public function getFormattedRemainingBudgetLimit(): string
  {
    return number_format($this->getRemainingBudgetLimit(), 2);
  }
}
