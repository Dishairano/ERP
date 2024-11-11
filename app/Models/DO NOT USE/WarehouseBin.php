<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseBin extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'warehouse_zone_id',
    'name',
    'code',
    'capacity',
    'status',
    'location',
    'dimensions',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'capacity' => 'decimal:2',
    'dimensions' => 'array'
  ];

  /**
   * Get the warehouse zone that owns the bin.
   */
  public function warehouseZone()
  {
    return $this->belongsTo(WarehouseZone::class);
  }

  /**
   * Get the stock levels for this bin.
   */
  public function stockLevels()
  {
    return $this->hasMany(StockLevel::class);
  }
}
