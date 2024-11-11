<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceJournalModal extends Model
{
  use HasFactory;

  protected $table = 'finance_journals';

  protected $fillable = [
    'number',
    'date',
    'type',
    'description',
    'reference',
    'currency',
    'exchange_rate',
    'total_debit',
    'total_credit',
    'created_by',
    'approved_by',
    'approved_at',
    'status',
    'notes'
  ];

  protected $casts = [
    'date' => 'date',
    'exchange_rate' => 'decimal:4',
    'total_debit' => 'decimal:2',
    'total_credit' => 'decimal:2',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the entries for the journal.
   */
  public function entries(): HasMany
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'journal_id');
  }

  /**
   * Get the user who created the journal.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the journal.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get all available journal types.
   */
  public static function getTypes(): array
  {
    return [
      'general',
      'sales',
      'purchases',
      'cash',
      'bank',
      'payroll',
      'fixed_assets',
      'adjusting',
      'closing'
    ];
  }

  /**
   * Check if the journal is balanced.
   */
  public function isBalanced(): bool
  {
    return $this->total_debit === $this->total_credit;
  }

  /**
   * Calculate journal totals.
   */
  public function calculateTotals(): void
  {
    $this->total_debit = $this->entries()->where('type', 'debit')->sum('amount');
    $this->total_credit = $this->entries()->where('type', 'credit')->sum('amount');
    $this->save();
  }

  /**
   * Get the balance difference.
   */
  public function getBalanceDifference(): float
  {
    return $this->total_debit - $this->total_credit;
  }

  /**
   * Format the total debit as currency.
   */
  public function getFormattedTotalDebit(): string
  {
    return number_format($this->total_debit, 2);
  }

  /**
   * Format the total credit as currency.
   */
  public function getFormattedTotalCredit(): string
  {
    return number_format($this->total_credit, 2);
  }

  /**
   * Format the balance difference as currency.
   */
  public function getFormattedBalanceDifference(): string
  {
    return number_format($this->getBalanceDifference(), 2);
  }

  /**
   * Check if the journal is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst(str_replace('_', ' ', $this->type));
  }

  /**
   * Scope a query to only include journals of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include journals within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include journals with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include approved journals.
   */
  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  /**
   * Scope a query to only include unapproved journals.
   */
  public function scopeUnapproved($query)
  {
    return $query->whereNull('approved_at');
  }

  /**
   * Scope a query to only include balanced journals.
   */
  public function scopeBalanced($query)
  {
    return $query->whereRaw('total_debit = total_credit');
  }

  /**
   * Scope a query to only include unbalanced journals.
   */
  public function scopeUnbalanced($query)
  {
    return $query->whereRaw('total_debit != total_credit');
  }
}
