<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCategory extends Model
{
  protected $fillable = [
    'name',
    'type',
    'description'
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
