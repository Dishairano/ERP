<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceCashFlowModal extends Model
{
  use HasFactory;

  protected $table = 'finance_cash_flows';

  protected $fillable = [
    'name',
    'type', // operating, investing, financing
    'category', // revenue, expense, receivable, payable, investment, loan, other
    'period_type', // daily, weekly, monthly, quarterly, annually
    'start_date',
    'end_date',
    'currency',
    'exchange_rate',
    'opening_balance',
    'closing_balance',
    'net_cash_flow',
    'operating_cash_flow',
    'investing_cash_flow',
    'financing_cash_flow',
    'description',
    'notes',
    'status', // draft, published, archived
    'created_by'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'exchange_rate' => 'decimal:4',
    'opening_balance' => 'decimal:2',
    'closing_balance' => 'decimal:2',
    'net_cash_flow' => 'decimal:2',
    'operating_cash_flow' => 'decimal:2',
    'investing_cash_flow' => 'decimal:2',
    'financing_cash_flow' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the cash flow.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the cash flow entries.
   */
  public function entries(): HasMany
  {
    return $this->hasMany(CoreFinanceCashFlowEntryModal::class, 'cash_flow_id');
  }

  /**
   * Get all available cash flow types.
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
   * Get all available period types.
   */
  public static function getPeriodTypes(): array
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
   * Format the opening balance as currency.
   */
  public function getFormattedOpeningBalance(): string
  {
    return number_format($this->opening_balance, 2);
  }

  /**
   * Format the closing balance as currency.
   */
  public function getFormattedClosingBalance(): string
  {
    return number_format($this->closing_balance, 2);
  }

  /**
   * Format the net cash flow as currency.
   */
  public function getFormattedNetCashFlow(): string
  {
    return number_format($this->net_cash_flow, 2);
  }

  /**
   * Format the operating cash flow as currency.
   */
  public function getFormattedOperatingCashFlow(): string
  {
    return number_format($this->operating_cash_flow, 2);
  }

  /**
   * Format the investing cash flow as currency.
   */
  public function getFormattedInvestingCashFlow(): string
  {
    return number_format($this->investing_cash_flow, 2);
  }

  /**
   * Format the financing cash flow as currency.
   */
  public function getFormattedFinancingCashFlow(): string
  {
    return number_format($this->financing_cash_flow, 2);
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
   * Get a human-readable period type name.
   */
  public function getPeriodTypeName(): string
  {
    return ucfirst($this->period_type);
  }

  /**
   * Calculate the period duration in days.
   */
  public function getPeriodDuration(): int
  {
    return $this->start_date->diffInDays($this->end_date) + 1;
  }

  /**
   * Calculate the daily average cash flow.
   */
  public function getDailyAverageCashFlow(): float
  {
    $duration = $this->getPeriodDuration();
    return $duration > 0 ? $this->net_cash_flow / $duration : 0;
  }

  /**
   * Format the daily average cash flow as currency.
   */
  public function getFormattedDailyAverageCashFlow(): string
  {
    return number_format($this->getDailyAverageCashFlow(), 2);
  }

  /**
   * Calculate the cash burn rate (negative cash flow per day).
   */
  public function getCashBurnRate(): float
  {
    $duration = $this->getPeriodDuration();
    return $duration > 0 && $this->net_cash_flow < 0 ?
      abs($this->net_cash_flow) / $duration : 0;
  }

  /**
   * Format the cash burn rate as currency.
   */
  public function getFormattedCashBurnRate(): string
  {
    return number_format($this->getCashBurnRate(), 2);
  }

  /**
   * Calculate the runway in days (if cash burn rate is positive).
   */
  public function getRunwayDays(): ?int
  {
    $burnRate = $this->getCashBurnRate();
    return $burnRate > 0 ?
      (int)($this->closing_balance / $burnRate) : null;
  }

  /**
   * Calculate the operating cash flow ratio.
   */
  public function getOperatingCashFlowRatio(): float
  {
    return $this->operating_cash_flow != 0 ?
      $this->net_cash_flow / abs($this->operating_cash_flow) : 0;
  }

  /**
   * Format the operating cash flow ratio.
   */
  public function getFormattedOperatingCashFlowRatio(): string
  {
    return number_format($this->getOperatingCashFlowRatio(), 2);
  }

  /**
   * Scope a query to only include cash flows of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include cash flows in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include cash flows with a specific period type.
   */
  public function scopeWithPeriodType($query, $periodType)
  {
    return $query->where('period_type', $periodType);
  }

  /**
   * Scope a query to only include cash flows with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include cash flows within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->where(function ($q) use ($startDate, $endDate) {
      $q->whereBetween('start_date', [$startDate, $endDate])
        ->orWhereBetween('end_date', [$startDate, $endDate])
        ->orWhere(function ($q) use ($startDate, $endDate) {
          $q->where('start_date', '<=', $startDate)
            ->where('end_date', '>=', $endDate);
        });
    });
  }

  /**
   * Scope a query to only include cash flows with positive net cash flow.
   */
  public function scopePositiveCashFlow($query)
  {
    return $query->where('net_cash_flow', '>', 0);
  }

  /**
   * Scope a query to only include cash flows with negative net cash flow.
   */
  public function scopeNegativeCashFlow($query)
  {
    return $query->where('net_cash_flow', '<', 0);
  }

  /**
   * Scope a query to only include cash flows with high burn rate.
   */
  public function scopeHighBurnRate($query, $threshold)
  {
    return $query->where('net_cash_flow', '<', 0)
      ->whereRaw('ABS(net_cash_flow) / DATEDIFF(end_date, start_date) >= ?', [$threshold]);
  }

  /**
   * Scope a query to only include cash flows with low runway.
   */
  public function scopeLowRunway($query, $days)
  {
    return $query->where('net_cash_flow', '<', 0)
      ->whereRaw('closing_balance / (ABS(net_cash_flow) / DATEDIFF(end_date, start_date)) <= ?', [$days]);
  }
}
