<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueStream extends Model
{
  protected $fillable = [
    'name',
    'description',
    'expected_amount',
    'currency'
  ];

  public function budgets()
  {
    return $this->hasMany(Budget::class);
  }

  public function getTotalBudgetAttribute()
  {
    return $this->budgets->sum('planned_amount');
  }

  public function getTotalActualSpendingAttribute()
  {
    return $this->budgets->sum('actual_amount');
  }
}
