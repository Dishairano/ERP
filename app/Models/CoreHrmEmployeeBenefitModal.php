<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmEmployeeBenefitModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_employee_benefits';

  protected $fillable = [
    'employee_id',
    'benefit_type', // health_insurance, life_insurance, dental, vision, retirement, stock_options, etc.
    'provider',
    'policy_number',
    'coverage_amount',
    'premium_amount',
    'employer_contribution',
    'employee_contribution',
    'start_date',
    'end_date',
    'renewal_date',
    'dependents_covered',
    'coverage_details',
    'deduction_frequency', // monthly, bi-weekly, weekly
    'payment_method',
    'beneficiary_name',
    'beneficiary_relation',
    'beneficiary_contact',
    'documents',
    'notes',
    'status', // active, inactive, pending
    'created_by'
  ];

  protected $casts = [
    'coverage_amount' => 'decimal:2',
    'premium_amount' => 'decimal:2',
    'employer_contribution' => 'decimal:2',
    'employee_contribution' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date',
    'renewal_date' => 'date',
    'dependents_covered' => 'boolean',
    'documents' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the benefit.
   */
  public function employee(): BelongsTo
  {
    return $this->belongsTo(CoreHrmEmployeeModal::class, 'employee_id');
  }

  /**
   * Get the creator of the record.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all available benefit types.
   */
  public static function getBenefitTypes(): array
  {
    return [
      'health_insurance',
      'life_insurance',
      'dental',
      'vision',
      'retirement',
      'stock_options',
      'disability',
      'wellness_program',
      'education_assistance',
      'child_care',
      'transportation',
      'gym_membership',
      'meal_allowance',
      'housing_allowance',
      'phone_allowance',
      'other'
    ];
  }

  /**
   * Get all available deduction frequencies.
   */
  public static function getDeductionFrequencies(): array
  {
    return [
      'monthly',
      'bi-weekly',
      'weekly'
    ];
  }

  /**
   * Calculate total monthly cost.
   */
  public function getTotalMonthlyCostAttribute(): float
  {
    return $this->premium_amount;
  }

  /**
   * Calculate total annual cost.
   */
  public function getTotalAnnualCostAttribute(): float
  {
    return $this->premium_amount * 12;
  }

  /**
   * Calculate monthly employee cost.
   */
  public function getMonthlyEmployeeCostAttribute(): float
  {
    return $this->employee_contribution;
  }

  /**
   * Calculate annual employee cost.
   */
  public function getAnnualEmployeeCostAttribute(): float
  {
    return $this->employee_contribution * 12;
  }

  /**
   * Calculate monthly employer cost.
   */
  public function getMonthlyEmployerCostAttribute(): float
  {
    return $this->employer_contribution;
  }

  /**
   * Calculate annual employer cost.
   */
  public function getAnnualEmployerCostAttribute(): float
  {
    return $this->employer_contribution * 12;
  }

  /**
   * Check if the benefit is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active' &&
      (!$this->end_date || $this->end_date->isFuture());
  }

  /**
   * Check if the benefit is pending renewal.
   */
  public function isPendingRenewal(): bool
  {
    return $this->renewal_date &&
      $this->renewal_date->isFuture() &&
      $this->renewal_date->diffInDays(now()) <= 30;
  }

  /**
   * Check if the benefit is expired.
   */
  public function isExpired(): bool
  {
    return $this->end_date && $this->end_date->isPast();
  }

  /**
   * Scope a query to only include active benefits.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active')
      ->where(function ($q) {
        $q->whereNull('end_date')
          ->orWhere('end_date', '>=', now());
      });
  }

  /**
   * Scope a query to only include inactive benefits.
   */
  public function scopeInactive($query)
  {
    return $query->where('status', 'inactive');
  }

  /**
   * Scope a query to only include pending benefits.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include benefits of a specific type.
   */
  public function scopeOfType($query, $type)
  {
    return $query->where('benefit_type', $type);
  }

  /**
   * Scope a query to only include benefits with a specific provider.
   */
  public function scopeWithProvider($query, $provider)
  {
    return $query->where('provider', $provider);
  }

  /**
   * Scope a query to only include benefits pending renewal.
   */
  public function scopePendingRenewal($query, $daysThreshold = 30)
  {
    return $query->where('renewal_date', '<=', now()->addDays($daysThreshold))
      ->where('renewal_date', '>', now());
  }

  /**
   * Scope a query to only include expired benefits.
   */
  public function scopeExpired($query)
  {
    return $query->where('end_date', '<', now());
  }

  /**
   * Scope a query to only include benefits covering dependents.
   */
  public function scopeWithDependents($query)
  {
    return $query->where('dependents_covered', true);
  }
}
