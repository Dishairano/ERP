<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastAccuracy extends Model
{
  protected $table = 'forecast_accuracy';

  protected $fillable = [
    'forecast_id',
    'actual_quantity',
    'actual_value',
    'accuracy_percentage',
    'bias',
    'variance_reason'
  ];

  protected $casts = [
    'actual_value' => 'decimal:2',
    'accuracy_percentage' => 'decimal:2',
    'bias' => 'decimal:2'
  ];

  public function forecast()
  {
    return $this->belongsTo(DemandForecast::class, 'forecast_id');
  }
}
