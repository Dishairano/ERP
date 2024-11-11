<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_id',
    'product_id',
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
   * Get the order that owns the item.
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the return items for this order item.
   */
  public function returnItems()
  {
    return $this->hasMany(SalesReturnItem::class, 'order_item_id');
  }
}
