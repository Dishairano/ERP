<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Customer;

class Project extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'client_id',
    'manager_id',
    'start_date',
    'end_date',
    'budget',
    'actual_cost',
    'scope',
    'status',
    'progress_percentage',
    'is_template'
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
    'budget' => 'decimal:2',
    'actual_cost' => 'decimal:2',
    'scope' => 'array',
    'progress_percentage' => 'integer',
    'is_template' => 'boolean'
  ];

  public function client()
  {
    return $this->belongsTo(Customer::class, 'client_id');
  }

  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  public function phases()
  {
    return $this->hasMany(ProjectPhase::class);
  }

  public function tasks()
  {
    return $this->hasMany(ProjectTask::class);
  }

  public function documents()
  {
    return $this->hasMany(ProjectDocument::class);
  }

  public function risks()
  {
    return $this->hasMany(ProjectRisk::class);
  }

  public function changes()
  {
    return $this->hasMany(ProjectChange::class);
  }

  public function feedback()
  {
    return $this->hasMany(ProjectFeedback::class);
  }

  public function kpis()
  {
    return $this->hasMany(ProjectKpi::class);
  }

  public function notifications()
  {
    return $this->hasMany(ProjectNotification::class);
  }

  public function timeRegistrations()
  {
    return $this->hasMany(ProjectTimeRegistration::class);
  }

  /**
   * Calculate the actual cost based on time registrations
   */
  public function calculateActualCost()
  {
    return $this->timeRegistrations()->sum('hours') * 50; // Assuming €50 per hour
  }

  /**
   * Update the actual cost
   */
  public function updateActualCost()
  {
    $this->actual_cost = $this->calculateActualCost();
    $this->save();
  }
}
