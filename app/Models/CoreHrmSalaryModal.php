<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class CoreHrmSalaryModal extends Model
{
  use HasFactory;

  protected $table = 'hrm_salaries';

  protected $fillable = [
    'employee_id',
    'basic_salary',
    'currency',
    'payment_type', // monthly, hourly, daily, weekly
    'effective_date',
    'end_date',
    'housing_allowance',
    'transportation_allowance',
    'meal_allowance',
    'phone_allowance',
    'other_allowances',
    'bonus_rate',
    'overtime_rate',
    'weekend_rate',
    'holiday_rate',
    'night_shift_rate',
    'tax_rate',
    'social_security_rate',
    'health_insurance_deduction',
    'pension_deduction',
    'loan_deduction',
    'other_deductions',
    'bank_name',
    'bank_account',
    'bank_branch',
    'payment_method', // bank transfer, cash, check
    'notes',
    'status', // active, inactive
    'created_by'
  ];

  protected $casts = [
    'basic_salary' => 'decimal:2',
    'housing_allowance' => 'decimal:2',
    'transportation_allowance' => 'decimal:2',
    'meal_allowance' => 'decimal:2',
    'phone_allowance' => 'decimal:2',
    'other_allowances' => 'decimal:2',
    'bonus_rate' => 'decimal:2',
    'overtime_rate' => 'decimal:2',
    'weekend_rate' => 'decimal:2',
    'holiday_rate' => 'decimal:2',
    'night_shift_rate' => 'decimal:2',
    'tax_rate' => 'decimal:2',
    'social_security_rate' => 'decimal:2',
    'health_insurance_deduction' => 'decimal:2',
    'pension_deduction' => 'decimal:2',
    'loan_deduction' => 'decimal:2',
    'other_deductions' => 'decimal:2',
    'effective_date' => 'date',
    'end_date' => 'date',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the salary.
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
   * Calculate total allowances.
   */
  public function getTotalAllowancesAttribute(): float
  {
    return $this->housing_allowance +
      $this->transportation_allowance +
      $this->meal_allowance +
      $this->phone_allowance +
      $this->other_allowances;
  }

  /**
   * Calculate total deductions.
   */
  public function getTotalDeductionsAttribute(): float
  {
    return $this->health_insurance_deduction +
      $this->pension_deduction +
      $this->loan_deduction +
      $this->other_deductions;
  }

  /**
   * Calculate gross salary.
   */
  public function getGrossSalaryAttribute(): float
  {
    return $this->basic_salary + $this->total_allowances;
  }

  /**
   * Calculate net salary.
   */
  public function getNetSalaryAttribute(): float
  {
    return $this->gross_salary - $this->total_deductions;
  }

  /**
   * Calculate annual salary.
   */
  public function getAnnualSalaryAttribute(): float
  {
    return $this->gross_salary * 12;
  }

  /**
   * Calculate monthly tax.
   */
  public function getMonthlyTaxAttribute(): float
  {
    return $this->gross_salary * ($this->tax_rate / 100);
  }

  /**
   * Calculate monthly social security.
   */
  public function getMonthlySocialSecurityAttribute(): float
  {
    return $this->gross_salary * ($this->social_security_rate / 100);
  }

  /**
   * Calculate overtime rate per hour.
   */
  public function getOvertimeRatePerHourAttribute(): float
  {
    if ($this->payment_type === 'monthly') {
      // Assuming 22 working days per month and 8 hours per day
      $hourlyRate = $this->basic_salary / (22 * 8);
      return $hourlyRate * ($this->overtime_rate / 100);
    }

    if ($this->payment_type === 'daily') {
      // Assuming 8 hours per day
      $hourlyRate = $this->basic_salary / 8;
      return $hourlyRate * ($this->overtime_rate / 100);
    }

    if ($this->payment_type === 'hourly') {
      return $this->basic_salary * ($this->overtime_rate / 100);
    }

    // Weekly
    // Assuming 40 hours per week
    $hourlyRate = $this->basic_salary / 40;
    return $hourlyRate * ($this->overtime_rate / 100);
  }

  /**
   * Scope a query to only include active salaries.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include inactive salaries.
   */
  public function scopeInactive($query)
  {
    return $query->where('status', 'inactive');
  }

  /**
   * Scope a query to only include salaries with a specific payment type.
   */
  public function scopeWithPaymentType($query, $paymentType)
  {
    return $query->where('payment_type', $paymentType);
  }

  /**
   * Scope a query to only include salaries with a specific payment method.
   */
  public function scopeWithPaymentMethod($query, $paymentMethod)
  {
    return $query->where('payment_method', $paymentMethod);
  }

  /**
   * Scope a query to only include salaries within a date range.
   */
  public function scopeInDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('effective_date', [$startDate, $endDate])
      ->orWhereBetween('end_date', [$startDate, $endDate])
      ->orWhere(function ($q) use ($startDate, $endDate) {
        $q->where('effective_date', '<=', $startDate)
          ->where('end_date', '>=', $endDate);
      });
  }

  /**
   * Scope a query to only include salaries above a certain amount.
   */
  public function scopeAboveAmount($query, $amount)
  {
    return $query->where('basic_salary', '>=', $amount);
  }

  /**
   * Scope a query to only include salaries below a certain amount.
   */
  public function scopeBelowAmount($query, $amount)
  {
    return $query->where('basic_salary', '<=', $amount);
  }
}
