<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'employee_id',
    'period',
    'base_salary',
    'total_allowances',
    'total_deductions',
    'net_salary',
    'status',
    'processed_at',
    'processed_by',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'period' => 'date',
    'base_salary' => 'decimal:2',
    'total_allowances' => 'decimal:2',
    'total_deductions' => 'decimal:2',
    'net_salary' => 'decimal:2',
    'processed_at' => 'datetime'
  ];

  /**
   * Get the employee that owns the payroll.
   */
  public function employee()
  {
    return $this->belongsTo(Employee::class);
  }

  /**
   * Get the components for this payroll.
   */
  public function components()
  {
    return $this->hasMany(PayrollComponent::class);
  }

  /**
   * Get the user who processed the payroll.
   */
  public function processor()
  {
    return $this->belongsTo(User::class, 'processed_by');
  }
}
