<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'purchase_requisition_id',
    'product_id',
    'quantity',
    'estimated_unit_price',
    'estimated_total_price',
    'specifications',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'estimated_unit_price' => 'decimal:2',
    'estimated_total_price' => 'decimal:2',
    'specifications' => 'array'
  ];

  /**
   * Get the purchase requisition that owns the item.
   */
  public function purchaseRequisition()
  {
    return $this->belongsTo(PurchaseRequisition::class);
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
