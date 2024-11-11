<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreProjectDashboardModal extends Model
{
  protected $table = 'projects';

  protected $fillable = [
    'name',
    'description',
    'start_date',
    'end_date',
    'status',
    'priority',
    'budget',
    'manager_id',
    'client_id',
    'progress',
    'is_active'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'budget' => 'decimal:2',
    'progress' => 'integer',
    'is_active' => 'boolean'
  ];

  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  public function client()
  {
    return $this->belongsTo(User::class, 'client_id');
  }

  public function tasks()
  {
    return $this->hasMany(Task::class);
  }

  public function team()
  {
    return $this->belongsToMany(User::class, 'project_team_members')
      ->withPivot('role')
      ->withTimestamps();
  }

  public function risks()
  {
    return $this->hasMany(Risk::class);
  }

  public function milestones()
  {
    return $this->hasMany(Milestone::class);
  }

  public function documents()
  {
    return $this->hasMany(Document::class);
  }

  public function budgets()
  {
    return $this->hasMany(Budget::class);
  }

  public function timeRegistrations()
  {
    return $this->hasMany(TimeRegistration::class);
  }

  public function getProgressStatusAttribute()
  {
    if ($this->progress < 25) {
      return 'Early Stage';
    } elseif ($this->progress < 50) {
      return 'In Progress';
    } elseif ($this->progress < 75) {
      return 'Advanced Stage';
    } else {
      return 'Near Completion';
    }
  }

  public function getBudgetStatusAttribute()
  {
    $spent = $this->timeRegistrations()->sum('cost');
    if ($spent > $this->budget) {
      return 'Over Budget';
    } elseif ($spent >= ($this->budget * 0.8)) {
      return 'Warning';
    } else {
      return 'On Track';
    }
  }

  public function getScheduleStatusAttribute()
  {
    if (!$this->end_date) {
      return 'No Deadline';
    }

    $today = now();
    $deadline = $this->end_date;
    $totalDays = $this->start_date->diffInDays($deadline);
    $daysLeft = $today->diffInDays($deadline, false);

    if ($daysLeft < 0) {
      return 'Overdue';
    } elseif ($daysLeft <= ($totalDays * 0.2)) {
      return 'Critical';
    } elseif ($daysLeft <= ($totalDays * 0.5)) {
      return 'Warning';
    } else {
      return 'On Schedule';
    }
  }
}
