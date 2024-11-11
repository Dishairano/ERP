<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceInvestmentAccountModal extends Model
{
  use HasFactory;

  protected $table = 'finance_investment_accounts';

  protected $fillable = [
    'code',
    'name',
    'account_number',
    'broker_name',
    'broker_code',
    'type', // brokerage, retirement, mutual_fund, other
    'currency',
    'opening_balance',
    'current_balance',
    'market_value',
    'unrealized_gain_loss',
    'realized_gain_loss',
    'last_valuation_date',
    'risk_level', // low, medium, high
    'investment_strategy',
    'target_allocation',
    'current_allocation',
    'rebalancing_frequency', // monthly, quarterly, annually, manual
    'last_rebalancing_date',
    'next_rebalancing_date',
    'contact_person',
    'contact_phone',
    'contact_email',
    'notes',
    'status',
    'created_by'
  ];

  protected $casts = [
    'opening_balance' => 'decimal:2',
    'current_balance' => 'decimal:2',
    'market_value' => 'decimal:2',
    'unrealized_gain_loss' => 'decimal:2',
    'realized_gain_loss' => 'decimal:2',
    'last_valuation_date' => 'date',
    'target_allocation' => 'json',
    'current_allocation' => 'json',
    'last_rebalancing_date' => 'date',
    'next_rebalancing_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the investment account.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the transactions for this investment account.
   */
  public function transactions(): HasMany
  {
    return $this->hasMany(CoreFinanceInvestmentTransactionModal::class, 'investment_account_id');
  }

  /**
   * Get the holdings for this investment account.
   */
  public function holdings(): HasMany
  {
    return $this->hasMany(CoreFinanceInvestmentHoldingModal::class, 'investment_account_id');
  }

  /**
   * Get all available account types.
   */
  public static function getTypes(): array
  {
    return [
      'brokerage',
      'retirement',
      'mutual_fund',
      'other'
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
   * Get all available rebalancing frequencies.
   */
  public static function getRebalancingFrequencies(): array
  {
    return [
      'monthly',
      'quarterly',
      'annually',
      'manual'
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
   * Format the current balance as currency.
   */
  public function getFormattedCurrentBalance(): string
  {
    return number_format($this->current_balance, 2);
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
   * Get the total return percentage.
   */
  public function getTotalReturnPercentage(): float
  {
    if ($this->opening_balance == 0) {
      return 0;
    }

    return (($this->market_value - $this->opening_balance) / $this->opening_balance) * 100;
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
   * Get a human-readable risk level name.
   */
  public function getRiskLevelName(): string
  {
    return ucfirst($this->risk_level);
  }

  /**
   * Get a human-readable rebalancing frequency name.
   */
  public function getRebalancingFrequencyName(): string
  {
    return ucfirst($this->rebalancing_frequency);
  }

  /**
   * Check if rebalancing is needed.
   */
  public function needsRebalancing(): bool
  {
    if ($this->rebalancing_frequency === 'manual') {
      return false;
    }

    if (!$this->next_rebalancing_date) {
      return true;
    }

    return now()->greaterThanOrEqualTo($this->next_rebalancing_date);
  }

  /**
   * Calculate the next rebalancing date based on frequency.
   */
  public function calculateNextRebalancingDate(): ?string
  {
    if ($this->rebalancing_frequency === 'manual') {
      return null;
    }

    $baseDate = $this->last_rebalancing_date ?? now();

    switch ($this->rebalancing_frequency) {
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
   * Scope a query to only include active accounts.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include accounts of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include accounts with a specific risk level.
   */
  public function scopeWithRiskLevel($query, $riskLevel)
  {
    return $query->where('risk_level', $riskLevel);
  }

  /**
   * Scope a query to only include accounts that need rebalancing.
   */
  public function scopeNeedsRebalancing($query)
  {
    return $query->where(function ($q) {
      $q->where('rebalancing_frequency', '!=', 'manual')
        ->where(function ($q) {
          $q->whereNull('next_rebalancing_date')
            ->orWhereDate('next_rebalancing_date', '<=', now());
        });
    });
  }
}
