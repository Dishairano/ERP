<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketTrend extends Model
{
  protected $fillable = [
    'trend_type',
    'trend_name',
    'description',
    'start_date',
    'end_date',
    'impact_factor',
    'affected_regions',
    'affected_products'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'impact_factor' => 'decimal:2',
    'affected_regions' => 'array',
    'affected_products' => 'array'
  ];

  public static function getTrendTypes()
  {
    return [
      'economic' => 'Economic',
      'weather' => 'Weather',
      'competition' => 'Competition',
      'technology' => 'Technology',
      'regulatory' => 'Regulatory',
      'social' => 'Social',
      'environmental' => 'Environmental'
    ];
  }
}
