<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceBankReconciliationModal extends Model
{
  use HasFactory;

  protected $table = 'finance_bank_reconciliations';

  protected $fillable = [
    'bank_account_id',
    'statement_date',
    'statement_balance',
    'bank_balance',
    'book_balance',
    'unreconciled_deposits',
    'unreconciled_withdrawals',
    'outstanding_checks',
    'adjusted_balance',
    'difference',
    'notes',
    'status', // draft, in_progress, completed, cancelled
    'completed_at',
    'completed_by',
    'created_by'
  ];

  protected $casts = [
    'statement_date' => 'date',
    'statement_balance' => 'decimal:2',
    'bank_balance' => 'decimal:2',
    'book_balance' => 'decimal:2',
    'unreconciled_deposits' => 'decimal:2',
    'unreconciled_withdrawals' => 'decimal:2',
    'outstanding_checks' => 'decimal:2',
    'adjusted_balance' => 'decimal:2',
    'difference' => 'decimal:2',
    'completed_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the bank account that owns the reconciliation.
   */
  public function bankAccount(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceBankAccountModal::class, 'bank_account_id');
  }

  /**
   * Get the user who created the reconciliation.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who completed the reconciliation.
   */
  public function completer(): BelongsTo
  {
    return $this->belongsTo(User::class, 'completed_by');
  }

  /**
   * Get the transactions for this reconciliation.
   */
  public function transactions(): HasMany
  {
    return $this->hasMany(CoreFinanceBankTransactionModal::class, 'reconciliation_id');
  }

  /**
   * Get all available statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'draft',
      'in_progress',
      'completed',
      'cancelled'
    ];
  }

  /**
   * Format the statement balance as currency.
   */
  public function getFormattedStatementBalance(): string
  {
    return number_format($this->statement_balance, 2);
  }

  /**
   * Format the bank balance as currency.
   */
  public function getFormattedBankBalance(): string
  {
    return number_format($this->bank_balance, 2);
  }

  /**
   * Format the book balance as currency.
   */
  public function getFormattedBookBalance(): string
  {
    return number_format($this->book_balance, 2);
  }

  /**
   * Format the unreconciled deposits as currency.
   */
  public function getFormattedUnreconciledDeposits(): string
  {
    return number_format($this->unreconciled_deposits, 2);
  }

  /**
   * Format the unreconciled withdrawals as currency.
   */
  public function getFormattedUnreconciledWithdrawals(): string
  {
    return number_format($this->unreconciled_withdrawals, 2);
  }

  /**
   * Format the outstanding checks as currency.
   */
  public function getFormattedOutstandingChecks(): string
  {
    return number_format($this->outstanding_checks, 2);
  }

  /**
   * Format the adjusted balance as currency.
   */
  public function getFormattedAdjustedBalance(): string
  {
    return number_format($this->adjusted_balance, 2);
  }

  /**
   * Format the difference as currency.
   */
  public function getFormattedDifference(): string
  {
    return number_format($this->difference, 2);
  }

  /**
   * Get a human-readable status name.
   */
  public function getStatusName(): string
  {
    return ucwords(str_replace('_', ' ', $this->status));
  }

  /**
   * Check if the reconciliation is completed.
   */
  public function isCompleted(): bool
  {
    return $this->status === 'completed';
  }

  /**
   * Check if the reconciliation is balanced.
   */
  public function isBalanced(): bool
  {
    return abs($this->difference) < 0.01;
  }

  /**
   * Calculate the adjusted balance.
   */
  public function calculateAdjustedBalance(): float
  {
    return $this->book_balance +
      $this->unreconciled_deposits -
      $this->unreconciled_withdrawals -
      $this->outstanding_checks;
  }

  /**
   * Calculate the difference.
   */
  public function calculateDifference(): float
  {
    return $this->statement_balance - $this->calculateAdjustedBalance();
  }

  /**
   * Update balances.
   */
  public function updateBalances(): void
  {
    $this->adjusted_balance = $this->calculateAdjustedBalance();
    $this->difference = $this->calculateDifference();
    $this->save();
  }

  /**
   * Scope a query to only include reconciliations with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include completed reconciliations.
   */
  public function scopeCompleted($query)
  {
    return $query->where('status', 'completed');
  }

  /**
   * Scope a query to only include unbalanced reconciliations.
   */
  public function scopeUnbalanced($query)
  {
    return $query->whereRaw('ABS(difference) >= 0.01');
  }

  /**
   * Scope a query to only include reconciliations within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('statement_date', [$startDate, $endDate]);
  }
}
