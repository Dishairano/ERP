<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceMaintenanceSchedule extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'resource_id',
    'maintenance_type',
    'scheduled_date',
    'completed_date',
    'status',
    'description',
    'estimated_duration_hours',
    'actual_duration_hours',
    'cost',
  ];

  protected $casts = [
    'scheduled_date' => 'datetime',
    'completed_date' => 'datetime',
    'estimated_duration_hours' => 'decimal:2',
    'actual_duration_hours' => 'decimal:2',
    'cost' => 'decimal:2',
  ];

  public function resource()
  {
    return $this->belongsTo(Resource::class);
  }

  public function isOverdue()
  {
    return $this->status !== 'completed' &&
      $this->scheduled_date->isPast();
  }

  public function markCompleted()
  {
    $this->update([
      'status' => 'completed',
      'completed_date' => now(),
    ]);
  }
}
