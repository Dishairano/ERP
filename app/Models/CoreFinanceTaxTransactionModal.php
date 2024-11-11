<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinanceTaxTransactionModal extends Model
{
  use HasFactory;

  protected $table = 'finance_tax_transactions';

  protected $fillable = [
    'tax_id',
    'reference_type', // receivable, payable, asset, other
    'reference_id',
    'date',
    'base_amount',
    'tax_amount',
    'currency',
    'exchange_rate',
    'is_inclusive',
    'status', // pending, filed, paid
    'filing_period', // YYYY-MM or YYYY-QQ
    'filing_date',
    'payment_date',
    'description',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'date' => 'date',
    'base_amount' => 'decimal:2',
    'tax_amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'is_inclusive' => 'boolean',
    'filing_date' => 'date',
    'payment_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the tax that owns the transaction.
   */
  public function tax(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceTaxModal::class, 'tax_id');
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
  public function journalEntries()
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'tax_transaction');
  }

  /**
   * Get all available reference types.
   */
  public static function getReferenceTypes(): array
  {
    return [
      'receivable',
      'payable',
      'asset',
      'other'
    ];
  }

  /**
   * Get all available statuses.
   */
  public static function getStatuses(): array
  {
    return [
      'pending',
      'filed',
      'paid'
    ];
  }

  /**
   * Format the base amount as currency.
   */
  public function getFormattedBaseAmount(): string
  {
    return number_format($this->base_amount, 2);
  }

  /**
   * Format the tax amount as currency.
   */
  public function getFormattedTaxAmount(): string
  {
    return number_format($this->tax_amount, 2);
  }

  /**
   * Get the base amount in base currency.
   */
  public function getBaseCurrencyBaseAmount(): float
  {
    return $this->base_amount * $this->exchange_rate;
  }

  /**
   * Get the tax amount in base currency.
   */
  public function getBaseCurrencyTaxAmount(): float
  {
    return $this->tax_amount * $this->exchange_rate;
  }

  /**
   * Format the base currency base amount as currency.
   */
  public function getFormattedBaseCurrencyBaseAmount(): string
  {
    return number_format($this->getBaseCurrencyBaseAmount(), 2);
  }

  /**
   * Format the base currency tax amount as currency.
   */
  public function getFormattedBaseCurrencyTaxAmount(): string
  {
    return number_format($this->getBaseCurrencyTaxAmount(), 2);
  }

  /**
   * Get a human-readable reference type name.
   */
  public function getReferenceName(): string
  {
    return ucfirst($this->reference_type);
  }

  /**
   * Get a human-readable status name.
   */
  public function getStatusName(): string
  {
    return ucfirst($this->status);
  }

  /**
   * Check if the transaction is filed.
   */
  public function isFiled(): bool
  {
    return in_array($this->status, ['filed', 'paid']);
  }

  /**
   * Check if the transaction is paid.
   */
  public function isPaid(): bool
  {
    return $this->status === 'paid';
  }

  /**
   * Scope a query to only include transactions of a specific tax.
   */
  public function scopeForTax($query, $taxId)
  {
    return $query->where('tax_id', $taxId);
  }

  /**
   * Scope a query to only include transactions of a specific reference type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('reference_type', $type);
  }

  /**
   * Scope a query to only include transactions within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include transactions for a specific filing period.
   */
  public function scopeInFilingPeriod($query, $period)
  {
    return $query->where('filing_period', $period);
  }

  /**
   * Scope a query to only include transactions with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include pending transactions.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include filed transactions.
   */
  public function scopeFiled($query)
  {
    return $query->where('status', 'filed');
  }

  /**
   * Scope a query to only include paid transactions.
   */
  public function scopePaid($query)
  {
    return $query->where('status', 'paid');
  }
}
