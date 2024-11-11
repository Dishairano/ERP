<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityInspection extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'work_order_id',
    'inspector_id',
    'inspection_date',
    'type',
    'result',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'inspection_date' => 'datetime'
  ];

  /**
   * Get the product being inspected.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the work order being inspected.
   */
  public function workOrder()
  {
    return $this->belongsTo(ProductionOrder::class, 'work_order_id');
  }

  /**
   * Get the inspector who performed the inspection.
   */
  public function inspector()
  {
    return $this->belongsTo(User::class, 'inspector_id');
  }

  /**
   * Get the measurements for this inspection.
   */
  public function measurements()
  {
    return $this->hasMany(QualityMeasurement::class);
  }

  /**
   * Get all of the inspection's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the inspection's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
