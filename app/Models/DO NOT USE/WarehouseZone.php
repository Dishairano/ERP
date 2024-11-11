<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseZone extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'warehouse_id',
    'name',
    'code',
    'type',
    'capacity',
    'status',
    'description',
    'location',
    'temperature_range',
    'humidity_range'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'capacity' => 'decimal:2',
    'temperature_range' => 'array',
    'humidity_range' => 'array'
  ];

  /**
   * Get the warehouse that owns the zone.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the bins in this zone.
   */
  public function bins()
  {
    return $this->hasMany(WarehouseBin::class);
  }

  /**
   * Get the stock levels in this zone.
   */
  public function stockLevels()
  {
    return $this->hasMany(StockLevel::class);
  }
}
