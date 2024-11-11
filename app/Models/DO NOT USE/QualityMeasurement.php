<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityMeasurement extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'quality_inspection_id',
    'parameter',
    'value',
    'unit',
    'specification_min',
    'specification_max',
    'result',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'value' => 'decimal:4',
    'specification_min' => 'decimal:4',
    'specification_max' => 'decimal:4'
  ];

  /**
   * Get the inspection that owns the measurement.
   */
  public function inspection()
  {
    return $this->belongsTo(QualityInspection::class, 'quality_inspection_id');
  }
}
