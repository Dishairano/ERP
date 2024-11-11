<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceAssignment extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'resource_id',
    'project_id',
    'user_id',
    'start_time',
    'end_time',
    'status',
    'notes',
    'actual_hours_used',
    'planned_hours',
  ];

  protected $casts = [
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'actual_hours_used' => 'decimal:2',
    'planned_hours' => 'decimal:2',
  ];

  public function resource()
  {
    return $this->belongsTo(Resource::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function getDurationAttribute()
  {
    return $this->start_time->diffInHours($this->end_time);
  }

  public function isOverdue()
  {
    return $this->status !== 'completed' && $this->end_time->isPast();
  }
}
