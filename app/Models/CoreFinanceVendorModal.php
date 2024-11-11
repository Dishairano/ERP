<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceVendorModal extends Model
{
  use HasFactory;

  protected $table = 'finance_vendors';

  protected $fillable = [
    'code',
    'name',
    'contact_person',
    'email',
    'phone',
    'mobile',
    'website',
    'tax_number',
    'registration_number',
    'address_line1',
    'address_line2',
    'city',
    'state',
    'postal_code',
    'country',
    'currency',
    'payment_terms',
    'credit_limit',
    'status',
    'notes',
    'created_by'
  ];

  protected $casts = [
    'credit_limit' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the vendor.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the payables for the vendor.
   */
  public function payables(): HasMany
  {
    return $this->hasMany(CoreFinancePayableModal::class, 'vendor_id');
  }

  /**
   * Get the payments for the vendor.
   */
  public function payments(): HasMany
  {
    return $this->hasMany(CoreFinancePayablePaymentModal::class, 'vendor_id');
  }

  /**
   * Get the total outstanding amount.
   */
  public function getTotalOutstandingAmount(): float
  {
    return $this->payables()
      ->where('remaining_amount', '>', 0)
      ->sum('remaining_amount');
  }

  /**
   * Get the total overdue amount.
   */
  public function getTotalOverdueAmount(): float
  {
    return $this->payables()
      ->where('remaining_amount', '>', 0)
      ->where('due_date', '<', now()->startOfDay())
      ->sum('remaining_amount');
  }

  /**
   * Check if vendor has exceeded credit limit.
   */
  public function hasExceededCreditLimit(): bool
  {
    return $this->getTotalOutstandingAmount() > $this->credit_limit;
  }

  /**
   * Get the remaining credit limit.
   */
  public function getRemainingCreditLimit(): float
  {
    return max(0, $this->credit_limit - $this->getTotalOutstandingAmount());
  }

  /**
   * Format the credit limit as currency.
   */
  public function getFormattedCreditLimit(): string
  {
    return number_format($this->credit_limit, 2);
  }

  /**
   * Get the full address.
   */
  public function getFullAddress(): string
  {
    $address = [$this->address_line1];

    if ($this->address_line2) {
      $address[] = $this->address_line2;
    }

    if ($this->city || $this->state || $this->postal_code) {
      $cityLine = [];
      if ($this->city) $cityLine[] = $this->city;
      if ($this->state) $cityLine[] = $this->state;
      if ($this->postal_code) $cityLine[] = $this->postal_code;
      $address[] = implode(', ', $cityLine);
    }

    if ($this->country) {
      $address[] = $this->country;
    }

    return implode("\n", $address);
  }

  /**
   * Get the full contact information.
   */
  public function getFullContactInfo(): array
  {
    $contact = [];

    if ($this->contact_person) {
      $contact['person'] = $this->contact_person;
    }

    if ($this->email) {
      $contact['email'] = $this->email;
    }

    if ($this->phone) {
      $contact['phone'] = $this->phone;
    }

    if ($this->mobile) {
      $contact['mobile'] = $this->mobile;
    }

    if ($this->website) {
      $contact['website'] = $this->website;
    }

    return $contact;
  }

  /**
   * Scope a query to only include active vendors.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include vendors with overdue payables.
   */
  public function scopeWithOverduePayables($query)
  {
    return $query->whereHas('payables', function ($q) {
      $q->where('remaining_amount', '>', 0)
        ->where('due_date', '<', now()->startOfDay());
    });
  }

  /**
   * Scope a query to only include vendors exceeding credit limit.
   */
  public function scopeExceedingCreditLimit($query)
  {
    return $query->whereHas('payables', function ($q) {
      $q->selectRaw('vendor_id, SUM(remaining_amount) as total_outstanding')
        ->groupBy('vendor_id')
        ->havingRaw('total_outstanding > finance_vendors.credit_limit');
    });
  }
}
