<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlChart extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'name',
    'type',
    'parameter',
    'ucl',
    'lcl',
    'target',
    'measurement_frequency'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'ucl' => 'decimal:4',
    'lcl' => 'decimal:4',
    'target' => 'decimal:4'
  ];

  /**
   * Get the product this chart is for.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the measurements for this chart.
   */
  public function measurements()
  {
    return $this->hasMany(ChartMeasurement::class);
  }

  /**
   * Get all of the chart's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the chart's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
