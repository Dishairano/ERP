<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinanceCashFlowEntryModal extends Model
{
  use HasFactory;

  protected $table = 'finance_cash_flow_entries';

  protected $fillable = [
    'cash_flow_id',
    'type', // operating, investing, financing
    'category', // revenue, expense, receivable, payable, investment, loan, other
    'date',
    'amount',
    'currency',
    'exchange_rate',
    'reference_type', // bank_transaction, investment_transaction, payable, receivable, other
    'reference_id',
    'description',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'date' => 'date',
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the cash flow that owns the entry.
   */
  public function cashFlow(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceCashFlowModal::class, 'cash_flow_id');
  }

  /**
   * Get the user who created the entry.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available entry types.
   */
  public static function getTypes(): array
  {
    return [
      'operating',
      'investing',
      'financing'
    ];
  }

  /**
   * Get all available categories.
   */
  public static function getCategories(): array
  {
    return [
      'revenue',
      'expense',
      'receivable',
      'payable',
      'investment',
      'loan',
      'other'
    ];
  }

  /**
   * Get all available reference types.
   */
  public static function getReferenceTypes(): array
  {
    return [
      'bank_transaction',
      'investment_transaction',
      'payable',
      'receivable',
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
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucfirst($this->type);
  }

  /**
   * Get a human-readable category name.
   */
  public function getCategoryName(): string
  {
    return ucwords(str_replace('_', ' ', $this->category));
  }

  /**
   * Get a human-readable reference type name.
   */
  public function getReferenceName(): string
  {
    return ucwords(str_replace('_', ' ', $this->reference_type));
  }

  /**
   * Check if the entry is an inflow.
   */
  public function isInflow(): bool
  {
    return $this->amount > 0;
  }

  /**
   * Check if the entry is an outflow.
   */
  public function isOutflow(): bool
  {
    return $this->amount < 0;
  }

  /**
   * Get the referenced model based on reference type.
   */
  public function getReferencedModel()
  {
    if (!$this->reference_type || !$this->reference_id) {
      return null;
    }

    switch ($this->reference_type) {
      case 'bank_transaction':
        return CoreFinanceBankTransactionModal::find($this->reference_id);
      case 'investment_transaction':
        return CoreFinanceInvestmentTransactionModal::find($this->reference_id);
      case 'payable':
        return CoreFinancePayableModal::find($this->reference_id);
      case 'receivable':
        return CoreFinanceReceivableModal::find($this->reference_id);
      default:
        return null;
    }
  }

  /**
   * Scope a query to only include entries of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include entries in a specific category.
   */
  public function scopeInCategory($query, $category)
  {
    return $query->where('category', $category);
  }

  /**
   * Scope a query to only include entries with a specific reference type.
   */
  public function scopeWithReferenceType($query, $referenceType)
  {
    return $query->where('reference_type', $referenceType);
  }

  /**
   * Scope a query to only include entries within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include inflows.
   */
  public function scopeInflows($query)
  {
    return $query->where('amount', '>', 0);
  }

  /**
   * Scope a query to only include outflows.
   */
  public function scopeOutflows($query)
  {
    return $query->where('amount', '<', 0);
  }

  /**
   * Scope a query to only include entries above a certain amount.
   */
  public function scopeAboveAmount($query, $amount)
  {
    return $query->where('amount', '>=', $amount);
  }

  /**
   * Scope a query to only include entries below a certain amount.
   */
  public function scopeBelowAmount($query, $amount)
  {
    return $query->where('amount', '<=', $amount);
  }

  /**
   * Scope a query to only include entries with specific currencies.
   */
  public function scopeInCurrency($query, $currency)
  {
    return $query->where('currency', $currency);
  }
}
