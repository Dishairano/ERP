<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationSchedule extends Model
{
  protected $fillable = [
    'integration_id',
    'frequency',
    'cron_expression',
    'preferred_time',
    'is_active'
  ];

  protected $casts = [
    'preferred_time' => 'datetime',
    'is_active' => 'boolean'
  ];

  public function integration()
  {
    return $this->belongsTo(DataIntegration::class);
  }
}
