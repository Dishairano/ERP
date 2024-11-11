<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLevel extends Model
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
    'warehouse_zone_id',
    'quantity',
    'minimum_level',
    'maximum_level',
    'reorder_point',
    'status',
    'last_counted_at',
    'last_counted_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'minimum_level' => 'decimal:2',
    'maximum_level' => 'decimal:2',
    'reorder_point' => 'decimal:2',
    'last_counted_at' => 'datetime'
  ];

  /**
   * Get the product that owns the stock level.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the warehouse that owns the stock level.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the warehouse zone that owns the stock level.
   */
  public function warehouseZone()
  {
    return $this->belongsTo(WarehouseZone::class);
  }

  /**
   * Get the user who last counted this stock.
   */
  public function lastCounter()
  {
    return $this->belongsTo(User::class, 'last_counted_by');
  }
}
