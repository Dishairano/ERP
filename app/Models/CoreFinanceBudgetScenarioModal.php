<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceBudgetScenarioModal extends Model
{
  use HasFactory;

  protected $table = 'finance_budget_scenarios';

  protected $fillable = [
    'budget_id',
    'name',
    'description',
    'type',
    'adjustment_percentage',
    'total_amount',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'notes'
  ];

  protected $casts = [
    'adjustment_percentage' => 'decimal:2',
    'total_amount' => 'decimal:2',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the scenario.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the scenario.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the budget that owns the scenario.
   */
  public function budget(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBudgetModal::class, 'budget_id');
  }

  /**
   * Get the line items for the scenario.
   */
  public function lineItems(): HasMany
  {
    return $this->hasMany(CoreFinanceBudgetScenarioLineItemModal::class, 'scenario_id');
  }

  /**
   * Get all available scenario types.
   */
  public static function getTypes(): array
  {
    return [
      'optimistic',
      'pessimistic',
      'most_likely',
      'custom'
    ];
  }

  /**
   * Create line items based on budget line items.
   */
  public function createLineItemsFromBudget(): void
  {
    foreach ($this->budget->lineItems as $budgetLineItem) {
      $amount = $budgetLineItem->amount;
      if ($this->adjustment_percentage) {
        $amount += ($amount * ($this->adjustment_percentage / 100));
      }

      $this->lineItems()->create([
        'name' => $budgetLineItem->name,
        'description' => $budgetLineItem->description,
        'amount' => $amount,
        'category' => $budgetLineItem->category,
        'start_date' => $budgetLineItem->start_date,
        'end_date' => $budgetLineItem->end_date,
        'notes' => "Created from budget line item: {$budgetLineItem->name}"
      ]);
    }

    $this->updateTotalAmount();
  }

  /**
   * Update the total amount based on line items.
   */
  public function updateTotalAmount(): void
  {
    $this->total_amount = $this->lineItems()->sum('amount');
    $this->save();
  }

  /**
   * Get the difference from the original budget.
   */
  public function getDifferenceFromBudget(): float
  {
    return $this->total_amount - $this->budget->total_amount;
  }

  /**
   * Get the percentage difference from the original budget.
   */
  public function getPercentageDifferenceFromBudget(): float
  {
    if ($this->budget->total_amount <= 0) {
      return 0;
    }

    return ($this->getDifferenceFromBudget() / $this->budget->total_amount) * 100;
  }

  /**
   * Apply this scenario to the budget.
   */
  public function applyToBudget(): void
  {
    foreach ($this->lineItems as $scenarioLineItem) {
      $budgetLineItem = $this->budget->lineItems()
        ->where('name', $scenarioLineItem->name)
        ->first();

      if ($budgetLineItem) {
        $budgetLineItem->update([
          'amount' => $scenarioLineItem->amount,
          'description' => $scenarioLineItem->description,
          'notes' => "Updated from scenario: {$this->name}"
        ]);
      }
    }

    $this->budget->updateTotalAmount();
  }

  /**
   * Check if the scenario is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Check if the scenario is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst(str_replace('_', ' ', $this->type));
  }

  /**
   * Format the total amount as currency.
   */
  public function getFormattedTotalAmount(): string
  {
    return number_format($this->total_amount, 2);
  }

  /**
   * Format the difference from budget as currency.
   */
  public function getFormattedDifferenceFromBudget(): string
  {
    return number_format($this->getDifferenceFromBudget(), 2);
  }

  /**
   * Scope a query to only include scenarios of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include active scenarios.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }
}
