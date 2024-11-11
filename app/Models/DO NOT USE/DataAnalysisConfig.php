<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataAnalysisConfig extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'name',
    'type',
    'criteria',
    'user_id',
    'is_template'
  ];

  protected $casts = [
    'criteria' => 'array',
    'is_template' => 'boolean'
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function results(): HasMany
  {
    return $this->hasMany(DataAnalysisResult::class, 'config_id');
  }

  public function visualizations(): HasMany
  {
    return $this->hasMany(DataVisualization::class, 'config_id');
  }

  public function forecasts(): HasMany
  {
    return $this->hasMany(DataForecast::class, 'config_id');
  }
}
