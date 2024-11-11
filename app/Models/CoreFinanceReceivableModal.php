<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceReceivableModal extends Model
{
  use HasFactory;

  protected $table = 'finance_receivables';

  protected $fillable = [
    'number',
    'customer_id',
    'date',
    'due_date',
    'amount',
    'paid_amount',
    'remaining_amount',
    'currency',
    'exchange_rate',
    'description',
    'reference',
    'payment_terms',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'notes'
  ];

  protected $casts = [
    'date' => 'date',
    'due_date' => 'date',
    'amount' => 'decimal:2',
    'paid_amount' => 'decimal:2',
    'remaining_amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the customer that owns the receivable.
   */
  public function customer(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceCustomerModal::class, 'customer_id');
  }

  /**
   * Get the user who created the receivable.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the receivable.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the payments for the receivable.
   */
  public function payments(): HasMany
  {
    return $this->hasMany(CoreFinanceReceivablePaymentModal::class, 'receivable_id');
  }

  /**
   * Get the journal entries for the receivable.
   */
  public function journalEntries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'receivable');
  }

  /**
   * Check if the receivable is fully paid.
   */
  public function isFullyPaid(): bool
  {
    return $this->remaining_amount <= 0;
  }

  /**
   * Check if the receivable is overdue.
   */
  public function isOverdue(): bool
  {
    return !$this->isFullyPaid() && now()->startOfDay()->gt($this->due_date);
  }

  /**
   * Get the days overdue.
   */
  public function getDaysOverdue(): int
  {
    if (!$this->isOverdue()) {
      return 0;
    }

    return now()->startOfDay()->diffInDays($this->due_date);
  }

  /**
   * Get the days until due.
   */
  public function getDaysUntilDue(): int
  {
    if ($this->isOverdue()) {
      return 0;
    }

    return now()->startOfDay()->diffInDays($this->due_date);
  }

  /**
   * Format the amount as currency.
   */
  public function getFormattedAmount(): string
  {
    return number_format($this->amount, 2);
  }

  /**
   * Format the paid amount as currency.
   */
  public function getFormattedPaidAmount(): string
  {
    return number_format($this->paid_amount, 2);
  }

  /**
   * Format the remaining amount as currency.
   */
  public function getFormattedRemainingAmount(): string
  {
    return number_format($this->remaining_amount, 2);
  }

  /**
   * Check if the receivable is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Scope a query to only include overdue receivables.
   */
  public function scopeOverdue($query)
  {
    return $query->where('remaining_amount', '>', 0)
      ->where('due_date', '<', now()->startOfDay());
  }

  /**
   * Scope a query to only include receivables due within days.
   */
  public function scopeDueWithinDays($query, $days)
  {
    return $query->where('remaining_amount', '>', 0)
      ->whereBetween('due_date', [now()->startOfDay(), now()->addDays($days)->endOfDay()]);
  }

  /**
   * Scope a query to only include unpaid receivables.
   */
  public function scopeUnpaid($query)
  {
    return $query->where('remaining_amount', '>', 0);
  }

  /**
   * Scope a query to only include fully paid receivables.
   */
  public function scopeFullyPaid($query)
  {
    return $query->where('remaining_amount', '<=', 0);
  }

  /**
   * Scope a query to only include receivables with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include approved receivables.
   */
  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  /**
   * Scope a query to only include unapproved receivables.
   */
  public function scopeUnapproved($query)
  {
    return $query->whereNull('approved_at');
  }
}
