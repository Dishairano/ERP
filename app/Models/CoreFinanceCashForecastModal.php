<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceCashForecastModal extends Model
{
  use HasFactory;

  protected $table = 'finance_cash_forecasts';

  protected $fillable = [
    'name',
    'type', // operating, investing, financing
    'category', // revenue, expense, receivable, payable, investment, loan, other
    'forecast_date',
    'amount',
    'currency',
    'exchange_rate',
    'probability', // percentage
    'is_recurring',
    'recurrence_pattern', // daily, weekly, monthly, quarterly, annually
    'recurrence_end_date',
    'reference_type', // customer, vendor, project, investment, loan, other
    'reference_id',
    'description',
    'notes',
    'status', // draft, confirmed, cancelled
    'created_by'
  ];

  protected $casts = [
    'forecast_date' => 'date',
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'probability' => 'integer',
    'is_recurring' => 'boolean',
    'recurrence_end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the forecast.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the forecast items.
   */
  public function items(): HasMany
  {
    return $this->hasMany(CoreFinanceCashForecastItemModal::class, 'forecast_id');
  }

  /**
   * Get all available forecast types.
   */
  public static function getTypes(): array
  {
    return [
      'operating',
      'investing',
      'financing'
    ];
  }

  /**
   * Get all available categories.
   */
  public static function getCategories(): array
  {
    return [
      'revenue',
      'expense',
      'receivable',
      'payable',
      'investment',
      'loan',
      'other'
    ];
  }

  /**
   * Get all available recurrence patterns.
   */
  public static function getRecurrencePatterns(): array
  {
    return [
      'daily',
      'weekly',
      'monthly',
      'quarterly',
      'annually'
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
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst($this->type);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucwords(str_replace('_', ' ', $this->category));
  }

  /**
   * Get a human-readable recurrence pattern name.
   */
  public function getRecurrencePatternName(): string
  {
    return ucfirst($this->recurrence_pattern);
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
   * Calculate the next occurrence date based on recurrence pattern.
   */
  public function getNextOccurrenceDate(): ?string
  {
    if (!$this->is_recurring || !$this->recurrence_pattern) {
      return null;
    }

    $baseDate = $this->forecast_date;

    switch ($this->recurrence_pattern) {
      case 'daily':
        return $baseDate->addDay();
      case 'weekly':
        return $baseDate->addWeek();
      case 'monthly':
        return $baseDate->addMonth();
      case 'quarterly':
        return $baseDate->addQuarter();
      case 'annually':
        return $baseDate->addYear();
      default:
        return null;
    }
  }

  /**
   * Check if the forecast is overdue.
   */
  public function isOverdue(): bool
  {
    return $this->forecast_date->isPast() && $this->status === 'confirmed';
  }

  /**
   * Check if the forecast is active.
   */
  public function isActive(): bool
  {
    if (!$this->is_recurring) {
      return !$this->forecast_date->isPast();
    }

    return !$this->recurrence_end_date || !$this->recurrence_end_date->isPast();
  }

  /**
   * Scope a query to only include forecasts of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include forecasts in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include forecasts with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include recurring forecasts.
   */
  public function scopeRecurring($query)
  {
    return $query->where('is_recurring', true);
  }

  /**
   * Scope a query to only include non-recurring forecasts.
   */
  public function scopeNonRecurring($query)
  {
    return $query->where('is_recurring', false);
  }

  /**
   * Scope a query to only include active forecasts.
   */
  public function scopeActive($query)
  {
    return $query->where(function ($q) {
      $q->where('is_recurring', false)
        ->whereDate('forecast_date', '>=', now())
        ->orWhere(function ($q) {
          $q->where('is_recurring', true)
            ->where(function ($q) {
              $q->whereNull('recurrence_end_date')
                ->orWhereDate('recurrence_end_date', '>=', now());
            });
        });
    });
  }

  /**
   * Scope a query to only include overdue forecasts.
   */
  public function scopeOverdue($query)
  {
    return $query->whereDate('forecast_date', '<', now())
      ->where('status', 'confirmed');
  }

  /**
   * Scope a query to only include forecasts within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('forecast_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include forecasts with probability above a threshold.
   */
  public function scopeWithProbabilityAbove($query, $threshold)
  {
    return $query->where('probability', '>=', $threshold);
  }
}
