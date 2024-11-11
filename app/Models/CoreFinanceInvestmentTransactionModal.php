<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceInvestmentTransactionModal extends Model
{
  use HasFactory;

  protected $table = 'finance_investment_transactions';

  protected $fillable = [
    'investment_account_id',
    'holding_id',
    'type', // buy, sell, dividend, interest, fee, transfer, other
    'transaction_date',
    'settlement_date',
    'quantity',
    'price',
    'amount',
    'commission',
    'fees',
    'total_amount',
    'currency',
    'exchange_rate',
    'gain_loss',
    'is_reinvested',
    'reference_number',
    'description',
    'notes',
    'status', // pending, settled, cancelled
    'created_by'
  ];

  protected $casts = [
    'transaction_date' => 'date',
    'settlement_date' => 'date',
    'quantity' => 'decimal:4',
    'price' => 'decimal:4',
    'amount' => 'decimal:2',
    'commission' => 'decimal:2',
    'fees' => 'decimal:2',
    'total_amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'gain_loss' => 'decimal:2',
    'is_reinvested' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the investment account that owns the transaction.
   */
  public function investmentAccount(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceInvestmentAccountModal::class, 'investment_account_id');
  }

  /**
   * Get the holding that owns the transaction.
   */
  public function holding(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceInvestmentHoldingModal::class, 'holding_id');
  }

  /**
   * Get the user who created the transaction.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the journal entries for the transaction.
   */
  public function journalEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'investment_transaction');
  }

  /**
   * Get all available transaction types.
   */
  public static function getTypes(): array
  {
    return [
      'buy',
      'sell',
      'dividend',
      'interest',
      'fee',
      'transfer',
      'other'
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
   * Format the price as currency.
   */
  public function getFormattedPrice(): string
  {
    return number_format($this->price, 4);
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Format the commission as currency.
   */
  public function getFormattedCommission(): string
  {
    return number_format($this->commission, 2);
  }

  /**
   * Format the fees as currency.
   */
  public function getFormattedFees(): string
  {
    return number_format($this->fees, 2);
  }

  /**
   * Format the total amount as currency.
   */
  public function getFormattedTotalAmount(): string
  {
    return number_format($this->total_amount, 2);
  }

  /**
   * Format the gain/loss as currency.
   */
  public function getFormattedGainLoss(): string
  {
    return number_format($this->gain_loss, 2);
  }

  /**
   * Get the amount in base currency.
   */
  public function getBaseCurrencyAmount(): float
  {
    return $this->total_amount * $this->exchange_rate;
  }

  /**
   * Format the base currency amount as currency.
   */
  public function getFormattedBaseCurrencyAmount(): string
  {
    return number_format($this->getBaseCurrencyAmount(), 2);
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst($this->type);
  }

  /**
   * Check if the transaction is a buy.
   */
  public function isBuy(): bool
  {
    return $this->type === 'buy';
  }

  /**
   * Check if the transaction is a sell.
   */
  public function isSell(): bool
  {
    return $this->type === 'sell';
  }

  /**
   * Check if the transaction is a dividend.
   */
  public function isDividend(): bool
  {
    return $this->type === 'dividend';
  }

  /**
   * Check if the transaction is an interest payment.
   */
  public function isInterest(): bool
  {
    return $this->type === 'interest';
  }

  /**
   * Check if the transaction is a fee.
   */
  public function isFee(): bool
  {
    return $this->type === 'fee';
  }

  /**
   * Check if the transaction is a transfer.
   */
  public function isTransfer(): bool
  {
    return $this->type === 'transfer';
  }

  /**
   * Check if the transaction affects quantity.
   */
  public function affectsQuantity(): bool
  {
    return in_array($this->type, ['buy', 'sell']);
  }

  /**
   * Check if the transaction affects cash balance.
   */
  public function affectsCashBalance(): bool
  {
    return !$this->is_reinvested;
  }

  /**
   * Scope a query to only include transactions of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include transactions with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include transactions within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('transaction_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include settled transactions.
   */
  public function scopeSettled($query)
  {
    return $query->where('status', 'settled');
  }

  /**
   * Scope a query to only include pending transactions.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include reinvested transactions.
   */
  public function scopeReinvested($query)
  {
    return $query->where('is_reinvested', true);
  }

  /**
   * Scope a query to only include cash transactions.
   */
  public function scopeCash($query)
  {
    return $query->where('is_reinvested', false);
  }
}
