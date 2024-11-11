<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetScenarioAdjustment extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'scenario_id',
    'category_id',
    'amount'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'amount' => 'decimal:2'
  ];

  /**
   * Get the scenario that owns the adjustment.
   */
  public function scenario()
  {
    return $this->belongsTo(BudgetScenario::class);
  }

  /**
   * Get the category that owns the adjustment.
   */
  public function category()
  {
    return $this->belongsTo(BudgetCategory::class);
  }
}
