<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformance extends Model
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
    'reporter_id',
    'type',
    'severity',
    'description',
    'immediate_action',
    'root_cause',
    'corrective_action',
    'preventive_action',
    'status'
  ];

  /**
   * Get the product associated with this non-conformance.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the work order associated with this non-conformance.
   */
  public function workOrder()
  {
    return $this->belongsTo(ProductionOrder::class, 'work_order_id');
  }

  /**
   * Get the user who reported this non-conformance.
   */
  public function reporter()
  {
    return $this->belongsTo(User::class, 'reporter_id');
  }

  /**
   * Get all of the non-conformance's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the non-conformance's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
