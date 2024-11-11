<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'code',
    'description',
    'manager_id',
    'parent_id',
    'is_active'
  ];

  protected $casts = [
    'is_active' => 'boolean',
  ];

  // Budget relationship
  public function budgets()
  {
    return $this->hasMany(Budget::class);
  }

  // Get the active budget
  public function budget()
  {
    return $this->hasOne(Budget::class)->where('is_active', true);
  }

  // Get all expenses related to this department
  public function expenses()
  {
    return $this->hasManyThrough(Expense::class, Budget::class);
  }

  // Get the department manager
  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  // Get the parent department
  public function parent()
  {
    return $this->belongsTo(Department::class, 'parent_id');
  }

  // Get child departments
  public function children()
  {
    return $this->hasMany(Department::class, 'parent_id');
  }

  // Calculate total budget amount
  public function getTotalBudgetAttribute()
  {
    return $this->budgets()->where('is_active', true)->sum('amount');
  }

  // Calculate total spent amount
  public function getTotalSpentAttribute()
  {
    return $this->expenses()->sum('amount');
  }

  // Calculate remaining budget
  public function getRemainingBudgetAttribute()
  {
    return $this->total_budget - $this->total_spent;
  }

  // Get budget utilization percentage
  public function getBudgetUtilizationAttribute()
  {
    if ($this->total_budget > 0) {
      return ($this->total_spent / $this->total_budget) * 100;
    }
    return 0;
  }
}
