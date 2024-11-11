<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'return_id',
    'order_item_id',
    'quantity',
    'price',
    'subtotal'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'price' => 'decimal:2',
    'subtotal' => 'decimal:2'
  ];

  /**
   * Get the return that owns the item.
   */
  public function return()
  {
    return $this->belongsTo(SalesReturn::class, 'return_id');
  }

  /**
   * Get the order item for this return item.
   */
  public function orderItem()
  {
    return $this->belongsTo(OrderItem::class);
  }
}
