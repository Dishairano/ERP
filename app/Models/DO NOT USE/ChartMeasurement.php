<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartMeasurement extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'control_chart_id',
    'value',
    'measured_at',
    'measured_by',
    'is_out_of_control',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'value' => 'decimal:4',
    'measured_at' => 'datetime',
    'is_out_of_control' => 'boolean'
  ];

  /**
   * Get the control chart that owns the measurement.
   */
  public function controlChart()
  {
    return $this->belongsTo(ControlChart::class);
  }

  /**
   * Get the user who took the measurement.
   */
  public function measuredBy()
  {
    return $this->belongsTo(User::class, 'measured_by');
  }
}
