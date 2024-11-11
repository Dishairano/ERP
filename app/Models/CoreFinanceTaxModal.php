<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;

class CoreFinanceTaxModal extends Model
{
  use HasFactory;

  protected $table = 'finance_taxes';

  protected $fillable = [
    'code',
    'name',
    'type', // sales_tax, vat, income_tax, withholding_tax, other
    'rate',
    'effective_from',
    'effective_to',
    'account_id',
    'is_recoverable',
    'is_compound',
    'applies_to', // sales, purchases, both
    'country',
    'region',
    'description',
    'status',
    'created_by'
  ];

  protected $casts = [
    'rate' => 'decimal:4',
    'effective_from' => 'date',
    'effective_to' => 'date',
    'is_recoverable' => 'boolean',
    'is_compound' => 'boolean',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the account that owns the tax.
   */
  public function account(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceAccountModal::class, 'account_id');
  }

  /**
   * Get the user who created the tax.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the tax transactions for this tax.
   */
  public function transactions(): HasMany
  {
    return $this->hasMany(CoreFinanceTaxTransactionModal::class, 'tax_id');
  }

  /**
   * Get all available tax types.
   */
  public static function getTypes(): array
  {
    return [
      'sales_tax',
      'vat',
      'income_tax',
      'withholding_tax',
      'other'
    ];
  }

  /**
   * Get all available applies_to options.
   */
  public static function getAppliesTo(): array
  {
    return [
      'sales',
      'purchases',
      'both'
    ];
  }

  /**
   * Calculate tax amount.
   */
  public function calculateTax(float $amount, bool $inclusive = false): float
  {
    if ($inclusive) {
      return $amount - ($amount / (1 + ($this->rate / 100)));
    }

    return $amount * ($this->rate / 100);
  }

  /**
   * Format the rate as percentage.
   */
  public function getFormattedRate(): string
  {
    return number_format($this->rate, 2) . '%';
  }

  /**
   * Get a human-readable type name.
   */
  public function getTypeName(): string
  {
    return ucwords(str_replace('_', ' ', $this->type));
  }

  /**
   * Get a human-readable applies_to name.
   */
  public function getAppliesToName(): string
  {
    return ucfirst($this->applies_to);
  }

  /**
   * Check if the tax is currently effective.
   */
  public function isEffective(): bool
  {
    $now = now()->startOfDay();
    return $now->gte($this->effective_from) &&
      ($this->effective_to === null || $now->lte($this->effective_to));
  }

  /**
   * Scope a query to only include active taxes.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include taxes of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('type', $type);
  }

  /**
   * Scope a query to only include taxes that apply to a specific transaction type.
   */
  public function scopeAppliesTo($query, $appliesTo)
  {
    return $query->where(function ($q) use ($appliesTo) {
      $q->where('applies_to', $appliesTo)
        ->orWhere('applies_to', 'both');
    });
  }

  /**
   * Scope a query to only include taxes effective at a given date.
   */
  public function scopeEffectiveAt($query, $date)
  {
    return $query->where('effective_from', '<=', $date)
      ->where(function ($q) use ($date) {
        $q->whereNull('effective_to')
          ->orWhere('effective_to', '>=', $date);
      });
  }

  /**
   * Scope a query to only include taxes for a specific country.
   */
  public function scopeForCountry($query, $country)
  {
    return $query->where('country', $country);
  }

  /**
   * Scope a query to only include taxes for a specific region.
   */
  public function scopeForRegion($query, $region)
  {
    return $query->where('region', $region);
  }

  /**
   * Scope a query to only include recoverable taxes.
   */
  public function scopeRecoverable($query)
  {
    return $query->where('is_recoverable', true);
  }

  /**
   * Scope a query to only include compound taxes.
   */
  public function scopeCompound($query)
  {
    return $query->where('is_compound', true);
  }
}
