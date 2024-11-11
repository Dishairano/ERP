<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAllocation extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'project_id',
    'resource_id',
    'start_date',
    'end_date',
    'hours_per_day',
    'role',
    'status',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'hours_per_day' => 'decimal:2'
  ];

  /**
   * Get the project that owns the allocation.
   */
  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  /**
   * Get the resource being allocated.
   */
  public function resource()
  {
    return $this->belongsTo(Resource::class);
  }

  /**
   * Get the user who created the allocation.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the allocation's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the allocation's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
