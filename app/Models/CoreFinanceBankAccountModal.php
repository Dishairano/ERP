<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceBankAccountModal extends Model
{
  use HasFactory;

  protected $table = 'finance_bank_accounts';

  protected $fillable = [
    'code',
    'name',
    'account_number',
    'bank_name',
    'branch_name',
    'swift_code',
    'iban',
    'routing_number',
    'currency',
    'type', // checking, savings, money_market, time_deposit, other
    'interest_rate',
    'minimum_balance',
    'opening_balance',
    'current_balance',
    'available_balance',
    'last_reconciliation_date',
    'reconciled_balance',
    'contact_person',
    'contact_phone',
    'contact_email',
    'address_line1',
    'address_line2',
    'city',
    'state',
    'postal_code',
    'country',
    'notes',
    'status',
    'created_by'
  ];

  protected $casts = [
    'interest_rate' => 'decimal:4',
    'minimum_balance' => 'decimal:2',
    'opening_balance' => 'decimal:2',
    'current_balance' => 'decimal:2',
    'available_balance' => 'decimal:2',
    'reconciled_balance' => 'decimal:2',
    'last_reconciliation_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the bank account.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the transactions for this bank account.
   */
  public function transactions(): HasMany
  {
    return $this->hasMany(CoreFinanceBankTransactionModal::class, 'bank_account_id');
  }

  /**
   * Get the reconciliations for this bank account.
   */
  public function reconciliations(): HasMany
  {
    return $this->hasMany(CoreFinanceBankReconciliationModal::class, 'bank_account_id');
  }

  /**
   * Get all available account types.
   */
  public static function getTypes(): array
  {
    return [
      'checking',
      'savings',
      'money_market',
      'time_deposit',
      'other'
    ];
  }

  /**
   * Format the interest rate as percentage.
   */
  public function getFormattedInterestRate(): string
  {
    return number_format($this->interest_rate, 2) . '%';
  }

  /**
   * Format the minimum balance as currency.
   */
  public function getFormattedMinimumBalance(): string
  {
    return number_format($this->minimum_balance, 2);
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
   * Format the available balance as currency.
   */
  public function getFormattedAvailableBalance(): string
  {
    return number_format($this->available_balance, 2);
  }

  /**
   * Format the reconciled balance as currency.
   */
  public function getFormattedReconciledBalance(): string
  {
    return number_format($this->reconciled_balance, 2);
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->type));
  }

  /**
   * Check if the account needs reconciliation.
   */
  public function needsReconciliation(): bool
  {
    if (!$this->last_reconciliation_date) {
      return true;
    }

    return now()->diffInDays($this->last_reconciliation_date) >= 30;
  }

  /**
   * Get the unreconciled balance.
   */
  public function getUnreconciledBalance(): float
  {
    return $this->current_balance - $this->reconciled_balance;
  }

  /**
   * Format the unreconciled balance as currency.
   */
  public function getFormattedUnreconciledBalance(): string
  {
    return number_format($this->getUnreconciledBalance(), 2);
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
   * Scope a query to only include accounts with a specific currency.
   */
  public function scopeWithCurrency($query, $currency)
  {
    return $query->where('currency', $currency);
  }

  /**
   * Scope a query to only include accounts that need reconciliation.
   */
  public function scopeNeedsReconciliation($query)
  {
    return $query->where(function ($q) {
      $q->whereNull('last_reconciliation_date')
        ->orWhereRaw('DATEDIFF(NOW(), last_reconciliation_date) >= 30');
    });
  }

  /**
   * Scope a query to only include accounts with balance below minimum.
   */
  public function scopeBelowMinimum($query)
  {
    return $query->whereRaw('current_balance < minimum_balance');
  }
}
