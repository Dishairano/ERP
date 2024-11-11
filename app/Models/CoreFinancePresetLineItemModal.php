<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreFinancePresetLineItemModal extends Model
{
  use HasFactory;

  protected $table = 'finance_preset_line_items';

  protected $fillable = [
    'preset_id',
    'name',
    'description',
    'amount',
    'category',
    'notes',
    'status'
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the preset that owns the line item.
   */
  public function preset(): BelongsTo
  {
    return $this->belongsTo(CoreFinancePresetDepartmentModal::class, 'preset_id');
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
   * Check if the line item amount is within the preset's remaining budget.
   */
  public function isWithinBudget(): bool
  {
    if (!$this->preset) {
      return false;
    }

    $totalLineItems = $this->preset->lineItems()
      ->where('id', '!=', $this->id)
      ->sum('amount');

    return ($totalLineItems + $this->amount) <= $this->preset->total_amount;
  }

  /**
   * Get the percentage this line item represents of the total preset budget.
   */
  public function getBudgetPercentage(): float
  {
    if (!$this->preset || $this->preset->total_amount <= 0) {
      return 0;
    }

    return ($this->amount / $this->preset->total_amount) * 100;
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
}
