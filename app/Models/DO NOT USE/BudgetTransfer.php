<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetTransfer extends Model
{
  protected $fillable = [
    'from_budget_id',
    'to_budget_id',
    'amount',
    'reason',
    'approved_by',
    'approved_at',
    'status'
  ];

  protected $dates = [
    'approved_at'
  ];

  public function fromBudget()
  {
    return $this->belongsTo(Budget::class, 'from_budget_id');
  }

  public function toBudget()
  {
    return $this->belongsTo(Budget::class, 'to_budget_id');
  }

  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }
}
