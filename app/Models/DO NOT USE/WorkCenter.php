<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkCenter extends Model
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
    'description',
    'capacity',
    'efficiency',
    'status',
    'location',
    'supervisor_id',
    'maintenance_schedule',
    'operating_hours'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'capacity' => 'decimal:2',
    'efficiency' => 'decimal:2',
    'maintenance_schedule' => 'array',
    'operating_hours' => 'array'
  ];

  /**
   * Get the production orders for this work center.
   */
  public function productionOrders()
  {
    return $this->hasMany(ProductionOrder::class);
  }

  /**
   * Get the supervisor of this work center.
   */
  public function supervisor()
  {
    return $this->belongsTo(User::class, 'supervisor_id');
  }

  /**
   * Get the maintenance records for this work center.
   */
  public function maintenanceRecords()
  {
    return $this->hasMany(MaintenanceRecord::class);
  }

  /**
   * Get the efficiency records for this work center.
   */
  public function efficiencyRecords()
  {
    return $this->hasMany(EfficiencyRecord::class);
  }
}
