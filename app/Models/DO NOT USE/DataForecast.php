<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataForecast extends Model
{
  protected $fillable = [
    'config_id',
    'forecast_data',
    'forecast_start',
    'forecast_end',
    'confidence_level'
  ];

  protected $casts = [
    'forecast_data' => 'array',
    'forecast_start' => 'date',
    'forecast_end' => 'date',
    'confidence_level' => 'decimal:2'
  ];

  public function config(): BelongsTo
  {
    return $this->belongsTo(DataAnalysisConfig::class, 'config_id');
  }
}
