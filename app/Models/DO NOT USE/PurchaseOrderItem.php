<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'purchase_order_id',
    'product_id',
    'quantity',
    'unit_price',
    'total_price',
    'tax_rate',
    'tax_amount',
    'discount_rate',
    'discount_amount',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'unit_price' => 'decimal:2',
    'total_price' => 'decimal:2',
    'tax_rate' => 'decimal:2',
    'tax_amount' => 'decimal:2',
    'discount_rate' => 'decimal:2',
    'discount_amount' => 'decimal:2'
  ];

  /**
   * Get the purchase order that owns the item.
   */
  public function purchaseOrder()
  {
    return $this->belongsTo(PurchaseOrder::class);
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
