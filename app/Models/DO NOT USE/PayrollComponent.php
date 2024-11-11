<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'payroll_id',
    'name',
    'type',
    'amount',
    'calculation_method',
    'calculation_basis',
    'is_taxable',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'amount' => 'decimal:2',
    'is_taxable' => 'boolean'
  ];

  /**
   * Get the payroll that owns the component.
   */
  public function payroll()
  {
    return $this->belongsTo(Payroll::class);
  }
}
