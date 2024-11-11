<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfficiencyRecord extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'work_center_id',
    'date',
    'planned_output',
    'actual_output',
    'efficiency_rate',
    'downtime',
    'quality_rate',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'date' => 'date',
    'planned_output' => 'decimal:2',
    'actual_output' => 'decimal:2',
    'efficiency_rate' => 'decimal:2',
    'downtime' => 'integer',
    'quality_rate' => 'decimal:2'
  ];

  /**
   * Get the work center that owns the efficiency record.
   */
  public function workCenter()
  {
    return $this->belongsTo(WorkCenter::class);
  }
}
