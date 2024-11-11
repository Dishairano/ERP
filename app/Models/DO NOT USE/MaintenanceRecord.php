<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'work_center_id',
    'type',
    'description',
    'scheduled_date',
    'completion_date',
    'status',
    'performed_by',
    'cost',
    'notes',
    'next_maintenance_date'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'scheduled_date' => 'date',
    'completion_date' => 'date',
    'next_maintenance_date' => 'date',
    'cost' => 'decimal:2'
  ];

  /**
   * Get the work center that owns the maintenance record.
   */
  public function workCenter()
  {
    return $this->belongsTo(WorkCenter::class);
  }

  /**
   * Get the user who performed the maintenance.
   */
  public function performer()
  {
    return $this->belongsTo(User::class, 'performed_by');
  }
}
