<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandBudget extends Model
{
  protected $fillable = [
    'forecast_id',
    'budget_id',
    'allocated_amount',
    'actual_spend',
    'status'
  ];

  protected $casts = [
    'allocated_amount' => 'decimal:2',
    'actual_spend' => 'decimal:2'
  ];

  public function forecast()
  {
    return $this->belongsTo(DemandForecast::class, 'forecast_id');
  }

  public function budget()
  {
    return $this->belongsTo(Budget::class);
  }

  public static function getStatuses()
  {
    return [
      'planned' => 'Planned',
      'approved' => 'Approved',
      'in_progress' => 'In Progress',
      'completed' => 'Completed',
      'over_budget' => 'Over Budget',
      'under_budget' => 'Under Budget'
    ];
  }
}
