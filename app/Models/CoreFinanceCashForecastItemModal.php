<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinanceCashForecastItemModal extends Model
{
  use HasFactory;

  protected $table = 'finance_cash_forecast_items';

  protected $fillable = [
    'forecast_id',
    'date',
    'amount',
    'currency',
    'exchange_rate',
    'probability',
    'description',
    'notes',
    'status', // pending, realized, cancelled
    'realization_date',
    'realized_amount',
    'variance_amount',
    'variance_percentage',
    'created_by'
  ];

  protected $casts = [
    'date' => 'date',
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'probability' => 'integer',
    'realization_date' => 'date',
    'realized_amount' => 'decimal:2',
    'variance_amount' => 'decimal:2',
    'variance_percentage' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the forecast that owns the item.
   */
  public function forecast(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceCashForecastModal::class, 'forecast_id');
  }

  /**
   * Get the user who created the item.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Get the amount in base currency.
   */
  public function getBaseCurrencyAmount(): float
  {
    return $this->amount * $this->exchange_rate;
  }

  /**
   * Format the base currency amount as currency.
   */
  public function getFormattedBaseCurrencyAmount(): string
  {
    return number_format($this->getBaseCurrencyAmount(), 2);
  }

  /**
   * Format the probability as percentage.
   */
  public function getFormattedProbability(): string
  {
    return $this->probability . '%';
  }

  /**
   * Format the realized amount as currency.
   */
  public function getFormattedRealizedAmount(): string
  {
    return number_format($this->realized_amount, 2);
  }

  /**
   * Format the variance amount as currency.
   */
  public function getFormattedVarianceAmount(): string
  {
    return number_format($this->variance_amount, 2);
  }

  /**
   * Format the variance percentage.
   */
  public function getFormattedVariancePercentage(): string
  {
    return number_format($this->variance_percentage, 2) . '%';
  }

  /**
   * Get the weighted amount (amount * probability).
   */
  public function getWeightedAmount(): float
  {
    return $this->amount * ($this->probability / 100);
  }

  /**
   * Format the weighted amount as currency.
   */
  public function getFormattedWeightedAmount(): string
  {
    return number_format($this->getWeightedAmount(), 2);
  }

  /**
   * Check if the item is overdue.
   */
  public function isOverdue(): bool
  {
    return $this->date->isPast() && $this->status === 'pending';
  }

  /**
   * Check if the item is realized.
   */
  public function isRealized(): bool
  {
    return $this->status === 'realized';
  }

  /**
   * Check if the item is cancelled.
   */
  public function isCancelled(): bool
  {
    return $this->status === 'cancelled';
  }

  /**
   * Calculate variance when realizing the item.
   */
  public function calculateVariance(): void
  {
    if ($this->realized_amount === null) {
      return;
    }

    $this->variance_amount = $this->realized_amount - $this->amount;
    $this->variance_percentage = $this->amount != 0 ?
      ($this->variance_amount / abs($this->amount)) * 100 :
      0;
  }

  /**
   * Realize the forecast item.
   */
  public function realize(float $realizedAmount, ?string $realizationDate = null): void
  {
    $this->status = 'realized';
    $this->realized_amount = $realizedAmount;
    $this->realization_date = $realizationDate ?? now();
    $this->calculateVariance();
    $this->save();
  }

  /**
   * Cancel the forecast item.
   */
  public function cancel(): void
  {
    $this->status = 'cancelled';
    $this->save();
  }

  /**
   * Scope a query to only include items with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include pending items.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include realized items.
   */
  public function scopeRealized($query)
  {
    return $query->where('status', 'realized');
  }

  /**
   * Scope a query to only include cancelled items.
   */
  public function scopeCancelled($query)
  {
    return $query->where('status', 'cancelled');
  }

  /**
   * Scope a query to only include overdue items.
   */
  public function scopeOverdue($query)
  {
    return $query->whereDate('date', '<', now())
      ->where('status', 'pending');
  }

  /**
   * Scope a query to only include items within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include items with probability above a threshold.
   */
  public function scopeWithProbabilityAbove($query, $threshold)
  {
    return $query->where('probability', '>=', $threshold);
  }

  /**
   * Scope a query to only include items with variance above a threshold.
   */
  public function scopeWithVarianceAbove($query, $threshold)
  {
    return $query->where('status', 'realized')
      ->whereRaw('ABS(variance_percentage) >= ?', [$threshold]);
  }
}
