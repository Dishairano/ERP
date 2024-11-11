<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'type',
    'description',
    'status',
    'capabilities',
    'cost_per_hour',
    'cost_per_day',
    'capacity',
    'location_details',
  ];

  protected $casts = [
    'capabilities' => 'array',
    'location_details' => 'array',
    'cost_per_hour' => 'decimal:2',
    'cost_per_day' => 'decimal:2',
  ];

  public function assignments()
  {
    return $this->hasMany(ResourceAssignment::class);
  }

  public function maintenanceSchedules()
  {
    return $this->hasMany(ResourceMaintenanceSchedule::class);
  }

  public function costs()
  {
    return $this->hasMany(ResourceCost::class);
  }

  public function isAvailable($startTime, $endTime)
  {
    return !$this->assignments()
      ->where('status', '!=', 'cancelled')
      ->where(function ($query) use ($startTime, $endTime) {
        $query->whereBetween('start_time', [$startTime, $endTime])
          ->orWhereBetween('end_time', [$startTime, $endTime])
          ->orWhere(function ($q) use ($startTime, $endTime) {
            $q->where('start_time', '<=', $startTime)
              ->where('end_time', '>=', $endTime);
          });
      })->exists();
  }

  public function getCurrentUtilization()
  {
    $activeAssignments = $this->assignments()
      ->where('status', 'active')
      ->count();

    return ($activeAssignments / $this->capacity) * 100;
  }
}
