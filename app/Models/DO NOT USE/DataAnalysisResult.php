<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataAnalysisResult extends Model
{
  protected $fillable = [
    'config_id',
    'result_data',
    'analyzed_at'
  ];

  protected $casts = [
    'result_data' => 'array',
    'analyzed_at' => 'datetime'
  ];

  public function config(): BelongsTo
  {
    return $this->belongsTo(DataAnalysisConfig::class, 'config_id');
  }

  public function exports(): HasMany
  {
    return $this->hasMany(DataExport::class, 'result_id');
  }
}
