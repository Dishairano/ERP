<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PutawayOrderItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'putaway_order_id',
    'product_id',
    'bin_id',
    'quantity',
    'putaway_quantity',
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
    'putaway_quantity' => 'decimal:2'
  ];

  /**
   * Get the putaway order that owns the item.
   */
  public function putawayOrder()
  {
    return $this->belongsTo(PutawayOrder::class);
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
