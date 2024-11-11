<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceBankTransactionModal extends Model
{
  use HasFactory;

  protected $table = 'finance_bank_transactions';

  protected $fillable = [
    'bank_account_id',
    'reference_type', // deposit, withdrawal, transfer, payment, receipt, fee, interest, other
    'reference_id',
    'transaction_date',
    'value_date',
    'amount',
    'currency',
    'exchange_rate',
    'description',
    'reference_number',
    'check_number',
    'payee',
    'category',
    'is_reconciled',
    'reconciliation_id',
    'reconciliation_date',
    'notes',
    'status',
    'created_by'
  ];

  protected $casts = [
    'transaction_date' => 'date',
    'value_date' => 'date',
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'is_reconciled' => 'boolean',
    'reconciliation_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the bank account that owns the transaction.
   */
  public function bankAccount(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBankAccountModal::class, 'bank_account_id');
  }

  /**
   * Get the user who created the transaction.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the reconciliation that owns the transaction.
   */
  public function reconciliation(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBankReconciliationModal::class, 'reconciliation_id');
  }

  /**
   * Get the journal entries for the transaction.
   */
  public function journalEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'bank_transaction');
  }

  /**
   * Get all available reference types.
   */
  public static function getReferenceTypes(): array
  {
    return [
      'deposit',
      'withdrawal',
      'transfer',
      'payment',
      'receipt',
      'fee',
      'interest',
      'other'
    ];
  }

  /**
   * Get all available categories.
   */
  public static function getCategories(): array
  {
    return [
      'operations',
      'investments',
      'financing',
      'taxes',
      'fees',
      'interest',
      'other'
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
   * Get a human-readable reference type name.
   */
  public function getReferenceName(): string
  {
    return ucfirst($this->reference_type);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucfirst($this->category);
  }

  /**
   * Check if the transaction is a debit.
   */
  public function isDebit(): bool
  {
    return in_array($this->reference_type, ['withdrawal', 'transfer', 'payment', 'fee']);
  }

  /**
   * Check if the transaction is a credit.
   */
  public function isCredit(): bool
  {
    return in_array($this->reference_type, ['deposit', 'receipt', 'interest']);
  }

  /**
   * Scope a query to only include transactions of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('reference_type', $type);
  }

  /**
   * Scope a query to only include transactions in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include transactions within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('transaction_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include reconciled transactions.
   */
  public function scopeReconciled($query)
  {
    return $query->where('is_reconciled', true);
  }

  /**
   * Scope a query to only include unreconciled transactions.
   */
  public function scopeUnreconciled($query)
  {
    return $query->where('is_reconciled', false);
  }

  /**
   * Scope a query to only include transactions with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include transactions for a specific payee.
   */
  public function scopeForPayee($query, $payee)
  {
    return $query->where('payee', $payee);
  }
}
