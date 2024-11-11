<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_number',
    'product_id',
    'quantity',
    'work_center_id',
    'scheduled_date',
    'start_date',
    'completion_date',
    'status',
    'priority',
    'actual_quantity',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'scheduled_date' => 'date',
    'start_date' => 'date',
    'completion_date' => 'date',
    'quantity' => 'decimal:2',
    'actual_quantity' => 'decimal:2'
  ];

  /**
   * Get the product being produced.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the work center where production is taking place.
   */
  public function workCenter()
  {
    return $this->belongsTo(WorkCenter::class);
  }

  /**
   * Get the bill of materials for this production order.
   */
  public function billOfMaterials()
  {
    return $this->belongsTo(BillOfMaterial::class);
  }

  /**
   * Get the progress percentage of the production order.
   *
   * @return int
   */
  public function getProgressAttribute()
  {
    if ($this->status === 'completed') {
      return 100;
    }

    if ($this->status === 'pending') {
      return 0;
    }

    if ($this->actual_quantity && $this->quantity) {
      return min(round(($this->actual_quantity / $this->quantity) * 100), 100);
    }

    return 0;
  }
}
