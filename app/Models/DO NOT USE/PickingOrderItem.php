<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickingOrderItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'picking_order_id',
    'product_id',
    'bin_id',
    'quantity',
    'picked_quantity',
    'status',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'picked_quantity' => 'decimal:2'
  ];

  /**
   * Get the picking order that owns the item.
   */
  public function pickingOrder()
  {
    return $this->belongsTo(PickingOrder::class);
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the bin for this item.
   */
  public function bin()
  {
    return $this->belongsTo(WarehouseBin::class, 'bin_id');
  }
}
