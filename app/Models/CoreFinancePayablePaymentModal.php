<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreFinancePayablePaymentModal extends Model
{
  use HasFactory;

  protected $table = 'finance_payable_payments';

  protected $fillable = [
    'payable_id',
    'vendor_id',
    'payment_date',
    'amount',
    'currency',
    'exchange_rate',
    'payment_method',
    'reference_number',
    'bank_account',
    'description',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'notes'
  ];

  protected $casts = [
    'payment_date' => 'date',
    'amount' => 'decimal:2',
    'exchange_rate' => 'decimal:4',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the payable that owns the payment.
   */
  public function payable(): BelongsTo
  {
    return $this->belongsTo(CoreFinancePayableModal::class, 'payable_id');
  }

  /**
   * Get the vendor that owns the payment.
   */
  public function vendor(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceVendorModal::class, 'vendor_id');
  }

  /**
   * Get the user who created the payment.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the payment.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get all available payment methods.
   */
  public static function getPaymentMethods(): array
  {
    return [
      'bank_transfer',
      'check',
      'cash',
      'credit_card',
      'debit_card',
      'electronic',
      'other'
    ];
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
   * Get a human-readable payment method name.
   */
  public function getPaymentMethodName(): string
  {
    return ucwords(str_replace('_', ' ', $this->payment_method));
  }

  /**
   * Check if the payment is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Scope a query to only include payments with a specific status.
   */
  public function scopeWithStatus($query, $status)
  {
    return $query->where('status', $status);
  }

  /**
   * Scope a query to only include payments with a specific payment method.
   */
  public function scopeWithPaymentMethod($query, $method)
  {
    return $query->where('payment_method', $method);
  }

  /**
   * Scope a query to only include payments within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('payment_date', [$startDate, $endDate]);
  }

  /**
   * Scope a query to only include approved payments.
   */
  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  /**
   * Scope a query to only include unapproved payments.
   */
  public function scopeUnapproved($query)
  {
    return $query->whereNull('approved_at');
  }

  /**
   * Get the journal entries for the payment.
   */
  public function journalEntries()
  {
    return $this->hasMany(CoreFinanceJournalEntryModal::class, 'reference_id')
      ->where('reference_type', 'payable_payment');
  }
}
