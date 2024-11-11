<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinanceJournalEntryModal extends Model
{
  use HasFactory;

  protected $table = 'finance_journal_entries';

  protected $fillable = [
    'account_id',
    'journal_id',
    'type', // debit or credit
    'amount',
    'currency',
    'exchange_rate',
    'date',
    'description',
    'reference',
    'created_by',
    'approved_by',
    'approved_at',
    'status',
    'notes'
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'date' => 'date',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the account that owns the journal entry.
   */
  public function account(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceAccountModal::class, 'account_id');
  }

  /**
   * Get the journal that owns the entry.
   */
  public function journal(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceJournalModal::class, 'journal_id');
  }

  /**
   * Get the user who created the entry.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the entry.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the amount in base currency.
   */
  public function getBaseCurrencyAmount(): float
  {
    return $this->amount * $this->exchange_rate;
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Format the base currency amount as currency.
   */
  public function getFormattedBaseCurrencyAmount(): string
  {
    return number_format($this->getBaseCurrencyAmount(), 2);
  }

  /**
   * Check if the entry is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Check if the entry is a debit.
   */
  public function isDebit(): bool
  {
    return $this->type === 'debit';
  }

  /**
   * Check if the entry is a credit.
   */
  public function isCredit(): bool
  {
    return $this->type === 'credit';
  }

  /**
   * Get the entry's effect on the account balance.
   */
  public function getBalanceEffect(): float
  {
    return $this->isCredit() ? $this->amount : -$this->amount;
  }

  /**
   * Scope a query to only include entries of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include entries within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include entries with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include approved entries.
   */
  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  /**
   * Scope a query to only include unapproved entries.
   */
  public function scopeUnapproved($query)
  {
    return $query->whereNull('approved_at');
  }
}
