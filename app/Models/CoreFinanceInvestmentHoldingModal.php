<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceInvestmentHoldingModal extends Model
{
  use HasFactory;

  protected $table = 'finance_investment_holdings';

  protected $fillable = [
    'investment_account_id',
    'symbol',
    'name',
    'type', // stock, bond, mutual_fund, etf, option, other
    'category', // equity, fixed_income, commodity, real_estate, cash, other
    'quantity',
    'cost_basis',
    'average_cost',
    'current_price',
    'market_value',
    'unrealized_gain_loss',
    'realized_gain_loss',
    'annual_income',
    'yield_percentage',
    'allocation_percentage',
    'target_allocation_percentage',
    'last_trade_date',
    'last_dividend_date',
    'next_dividend_date',
    'dividend_frequency', // monthly, quarterly, semi_annual, annual
    'risk_level', // low, medium, high
    'sector',
    'industry',
    'country',
    'currency',
    'exchange_rate',
    'maturity_date',
    'coupon_rate',
    'notes',
    'status',
    'created_by'
  ];

  protected $casts = [
    'quantity' => 'decimal:4',
    'cost_basis' => 'decimal:2',
    'average_cost' => 'decimal:4',
    'current_price' => 'decimal:4',
    'market_value' => 'decimal:2',
    'unrealized_gain_loss' => 'decimal:2',
    'realized_gain_loss' => 'decimal:2',
    'annual_income' => 'decimal:2',
    'yield_percentage' => 'decimal:2',
    'allocation_percentage' => 'decimal:2',
    'target_allocation_percentage' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'coupon_rate' => 'decimal:2',
    'last_trade_date' => 'date',
    'last_dividend_date' => 'date',
    'next_dividend_date' => 'date',
    'maturity_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the investment account that owns the holding.
   */
  public function investmentAccount(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceInvestmentAccountModal::class, 'investment_account_id');
  }

  /**
   * Get the user who created the holding.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the transactions for this holding.
   */
  public function transactions(): HasMany
  {
    return $this->hasMany(CoreFinanceInvestmentTransactionModal::class, 'holding_id');
  }

  /**
   * Get all available holding types.
   */
  public static function getTypes(): array
  {
    return [
      'stock',
      'bond',
      'mutual_fund',
      'etf',
      'option',
      'other'
    ];
  }

  /**
   * Get all available categories.
   */
  public static function getCategories(): array
  {
    return [
      'equity',
      'fixed_income',
      'commodity',
      'real_estate',
      'cash',
      'other'
    ];
  }

  /**
   * Get all available dividend frequencies.
   */
  public static function getDividendFrequencies(): array
  {
    return [
      'monthly',
      'quarterly',
      'semi_annual',
      'annual'
    ];
  }

  /**
   * Get all available risk levels.
   */
  public static function getRiskLevels(): array
  {
    return [
      'low',
      'medium',
      'high'
    ];
  }

  /**
   * Format the quantity.
   */
  public function getFormattedQuantity(): string
  {
    return number_format($this->quantity, 4);
  }

  /**
   * Format the cost basis as currency.
   */
  public function getFormattedCostBasis(): string
  {
    return number_format($this->cost_basis, 2);
  }

  /**
   * Format the average cost as currency.
   */
  public function getFormattedAverageCost(): string
  {
    return number_format($this->average_cost, 4);
  }

  /**
   * Format the current price as currency.
   */
  public function getFormattedCurrentPrice(): string
  {
    return number_format($this->current_price, 4);
  }

  /**
   * Format the market value as currency.
   */
  public function getFormattedMarketValue(): string
  {
    return number_format($this->market_value, 2);
  }

  /**
   * Format the unrealized gain/loss as currency.
   */
  public function getFormattedUnrealizedGainLoss(): string
  {
    return number_format($this->unrealized_gain_loss, 2);
  }

  /**
   * Format the realized gain/loss as currency.
   */
  public function getFormattedRealizedGainLoss(): string
  {
    return number_format($this->realized_gain_loss, 2);
  }

  /**
   * Format the annual income as currency.
   */
  public function getFormattedAnnualIncome(): string
  {
    return number_format($this->annual_income, 2);
  }

  /**
   * Format the yield percentage.
   */
  public function getFormattedYieldPercentage(): string
  {
    return number_format($this->yield_percentage, 2) . '%';
  }

  /**
   * Format the allocation percentage.
   */
  public function getFormattedAllocationPercentage(): string
  {
    return number_format($this->allocation_percentage, 2) . '%';
  }

  /**
   * Format the target allocation percentage.
   */
  public function getFormattedTargetAllocationPercentage(): string
  {
    return number_format($this->target_allocation_percentage, 2) . '%';
  }

  /**
   * Get the total return percentage.
   */
  public function getTotalReturnPercentage(): float
  {
    if ($this->cost_basis == 0) {
      return 0;
    }

    return (($this->market_value - $this->cost_basis) / $this->cost_basis) * 100;
  }

  /**
   * Format the total return percentage.
   */
  public function getFormattedTotalReturnPercentage(): string
  {
    return number_format($this->getTotalReturnPercentage(), 2) . '%';
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->type));
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucwords(str_replace('_', ' ', $this->category));
  }

  /**
   * Get a human-readable dividend frequency name.
   */
  public function getDividendFrequencyName(): string
  {
    return ucwords(str_replace('_', ' ', $this->dividend_frequency));
  }

  /**
   * Get a human-readable risk level name.
   */
  public function getRiskLevelName(): string
  {
    return ucfirst($this->risk_level);
  }

  /**
   * Check if rebalancing is needed.
   */
  public function needsRebalancing(): bool
  {
    if (!$this->target_allocation_percentage) {
      return false;
    }

    return abs($this->allocation_percentage - $this->target_allocation_percentage) > 5;
  }

  /**
   * Calculate the rebalancing amount.
   */
  public function getRebalancingAmount(): float
  {
    if (!$this->target_allocation_percentage) {
      return 0;
    }

    $targetValue = $this->investmentAccount->market_value * ($this->target_allocation_percentage / 100);
    return $targetValue - $this->market_value;
  }

  /**
   * Scope a query to only include active holdings.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include holdings of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include holdings in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include holdings with a specific risk level.
   */
  public function scopeWithRiskLevel($query, $riskLevel)
  {
    return $query->where('risk_level', $riskLevel);
  }

  /**
   * Scope a query to only include holdings that need rebalancing.
   */
  public function scopeNeedsRebalancing($query)
  {
    return $query->whereNotNull('target_allocation_percentage')
      ->whereRaw('ABS(allocation_percentage - target_allocation_percentage) > 5');
  }
}
