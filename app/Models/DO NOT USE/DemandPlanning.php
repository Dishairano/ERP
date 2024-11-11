<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DemandPlanning extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'demand_forecasts';

  protected $fillable = [
    'product_id',
    'region_id',
    'created_by',
    'forecast_date',
    'forecast_quantity',
    'forecast_value',
    'forecast_method',
    'confidence_level',
    'seasonal_factors',
  ];

  protected $casts = [
    'forecast_date' => 'date',
    'seasonal_factors' => 'array',
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

  public function calculateAccuracy()
  {
    if (!$this->accuracy) {
      return null;
    }

    return [
      'accuracy_percentage' => $this->accuracy->accuracy_percentage,
      'bias' => $this->accuracy->bias,
      'variance_reason' => $this->accuracy->variance_reason,
    ];
  }

  public function getSeasonalTrends()
  {
    return $this->seasonal_factors ?? [];
  }

  public function applyMarketTrends()
  {
    $trends = MarketTrend::whereJsonContains('affected_products', $this->product_id)
      ->whereDate('start_date', '<=', $this->forecast_date)
      ->whereDate('end_date', '>=', $this->forecast_date)
      ->get();

    $impact = 1;
    foreach ($trends as $trend) {
      $impact *= (1 + $trend->impact_factor);
    }

    return $impact;
  }
}
