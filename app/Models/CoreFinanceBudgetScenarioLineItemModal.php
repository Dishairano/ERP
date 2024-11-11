<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreFinanceBudgetScenarioLineItemModal extends Model
{
  use HasFactory;

  protected $table = 'finance_budget_scenario_line_items';

  protected $fillable = [
    'scenario_id',
    'name',
    'description',
    'amount',
    'category',
    'start_date',
    'end_date',
    'status',
    'notes'
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the scenario that owns the line item.
   */
  public function scenario(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBudgetScenarioModal::class, 'scenario_id');
  }

  /**
   * Scope a query to only include active line items.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include line items in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include line items within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('start_date', [$startDate, $endDate])
      ->orWhereBetween('end_date', [$startDate, $endDate]);
  }

  /**
   * Get all available categories.
   */
  public static function getCategories(): array
  {
    return [
      'salary',
      'equipment',
      'supplies',
      'travel',
      'training',
      'software',
      'services',
      'utilities',
      'rent',
      'maintenance',
      'marketing',
      'insurance',
      'miscellaneous'
    ];
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucfirst(str_replace('_', ' ', $this->category));
  }

  /**
   * Check if the line item is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Create a copy of this line item.
   */
  public function duplicate(): self
  {
    $copy = $this->replicate();
    $copy->name = "{$this->name} (Copy)";
    $copy->save();

    return $copy;
  }

  /**
   * Get the difference from the original budget line item.
   */
  public function getDifferenceFromBudget(): float
  {
    $budgetLineItem = $this->scenario->budget->lineItems()
      ->where('name', $this->name)
      ->first();

    if (!$budgetLineItem) {
      return 0;
    }

    return $this->amount - $budgetLineItem->amount;
  }

  /**
   * Get the percentage difference from the original budget line item.
   */
  public function getPercentageDifferenceFromBudget(): float
  {
    $budgetLineItem = $this->scenario->budget->lineItems()
      ->where('name', $this->name)
      ->first();

    if (!$budgetLineItem || $budgetLineItem->amount <= 0) {
      return 0;
    }

    return ($this->getDifferenceFromBudget() / $budgetLineItem->amount) * 100;
  }

  /**
   * Check if the line item is within its date range.
   */
  public function isWithinDateRange(): bool
  {
    $today = now()->startOfDay();
    return $today->between($this->start_date, $this->end_date);
  }

  /**
   * Get the percentage this line item represents of the total scenario budget.
   */
  public function getScenarioPercentage(): float
  {
    if (!$this->scenario || $this->scenario->total_amount <= 0) {
      return 0;
    }

    return ($this->amount / $this->scenario->total_amount) * 100;
  }
}
