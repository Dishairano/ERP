<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiNotification extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'kpi_definition_id',
    'kpi_threshold_id',
    'kpi_value_id',
    'severity',
    'message',
    'recipients',
    'read_at'
  ];

  protected $casts = [
    'recipients' => 'array',
    'read_at' => 'datetime'
  ];

  public function definition()
  {
    return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
  }

  public function threshold()
  {
    return $this->belongsTo(KpiThreshold::class, 'kpi_threshold_id');
  }

  public function value()
  {
    return $this->belongsTo(KpiValue::class, 'kpi_value_id');
  }

  public function markAsRead()
  {
    $this->read_at = now();
    $this->save();
  }
}
