<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'budget_id',
    'name',
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
   * Get the budget that owns the category.
   */
  public function budget()
  {
    return $this->belongsTo(Budget::class);
  }

  /**
   * Get the scenario adjustments for this category.
   */
  public function adjustments()
  {
    return $this->hasMany(BudgetScenarioAdjustment::class, 'category_id');
  }
}
