<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreFinanceBudgetLineItemModal extends Model
{
  use HasFactory;

  protected $table = 'finance_budget_line_items';

  protected $fillable = [
    'budget_id',
    'name',
    'description',
    'amount',
    'allocated_amount',
    'remaining_amount',
    'category',
    'start_date',
    'end_date',
    'status',
    'notes'
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'allocated_amount' => 'decimal:2',
    'remaining_amount' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the budget that owns the line item.
   */
  public function budget(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBudgetModal::class, 'budget_id');
  }

  /**
   * Calculate the remaining amount.
   */
  public function calculateRemainingAmount(): void
  {
    $this->remaining_amount = $this->amount - $this->allocated_amount;
    $this->save();
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
   * Check if the line item is over allocated.
   */
  public function isOverAllocated(): bool
  {
    return $this->allocated_amount > $this->amount;
  }

  /**
   * Get the utilization percentage.
   */
  public function getUtilizationPercentage(): float
  {
    if ($this->amount <= 0) {
      return 0;
    }

    return ($this->allocated_amount / $this->amount) * 100;
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
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
    $copy->allocated_amount = 0;
    $copy->remaining_amount = $copy->amount;
    $copy->save();

    return $copy;
  }

  /**
   * Check if the line item has any remaining budget.
   */
  public function hasRemainingBudget(): bool
  {
    return $this->remaining_amount > 0;
  }

  /**
   * Check if the line item is within its date range.
   */
  public function isWithinDateRange(): bool
  {
    $today = now()->startOfDay();
    return $today->between($this->start_date, $this->end_date);
  }
}
