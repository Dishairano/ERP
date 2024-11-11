<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'warehouse_id',
    'type', // in, out
    'quantity',
    'reference_type', // order, adjustment, transfer
    'reference_id',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the product that owns the stock movement.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the warehouse that owns the stock movement.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the reference model (polymorphic).
   */
  public function reference()
  {
    return $this->morphTo();
  }
}
