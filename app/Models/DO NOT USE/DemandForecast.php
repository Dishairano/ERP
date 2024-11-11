<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemandForecast extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'product_id',
    'region_id',
    'created_by',
    'forecast_date',
    'forecast_quantity',
    'forecast_value',
    'forecast_method',
    'confidence_level',
    'seasonal_factors'
  ];

  protected $casts = [
    'forecast_date' => 'date',
    'seasonal_factors' => 'array',
    'forecast_value' => 'decimal:2',
    'confidence_level' => 'decimal:2'
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function region()
  {
    return $this->belongsTo(Department::class, 'region_id');
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function accuracy()
  {
    return $this->hasOne(ForecastAccuracy::class, 'forecast_id');
  }

  public function budget()
  {
    return $this->hasOne(DemandBudget::class, 'forecast_id');
  }

  public static function getForecastMethods()
  {
    return [
      'linear_regression' => 'Linear Regression',
      'moving_average' => 'Moving Average',
      'exponential_smoothing' => 'Exponential Smoothing',
      'seasonal_decomposition' => 'Seasonal Decomposition',
      'arima' => 'ARIMA',
      'neural_network' => 'Neural Network'
    ];
  }
}
