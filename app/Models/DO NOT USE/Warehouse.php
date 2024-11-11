<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'code',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'manager_id',
    'status',
    'capacity',
    'type',
    'operating_hours'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'capacity' => 'decimal:2',
    'operating_hours' => 'array'
  ];

  /**
   * Get the stock movements for this warehouse.
   */
  public function stockMovements()
  {
    return $this->hasMany(StockMovement::class);
  }

  /**
   * Get the manager of this warehouse.
   */
  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  /**
   * Get the zones in this warehouse.
   */
  public function zones()
  {
    return $this->hasMany(WarehouseZone::class);
  }

  /**
   * Get the current stock levels in this warehouse.
   */
  public function stockLevels()
  {
    return $this->hasMany(StockLevel::class);
  }
}
