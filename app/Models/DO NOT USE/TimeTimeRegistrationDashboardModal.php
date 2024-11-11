<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeTimeRegistrationDashboardModal extends Model
{
  protected $table = 'time_registrations';

  protected $fillable = [
    'user_id',
    'project_id',
    'task_id',
    'date',
    'start_time',
    'end_time',
    'duration',
    'description',
    'status',
    'approved_by',
    'approved_at',
    'billable',
    'cost_rate',
    'bill_rate'
  ];

  protected $casts = [
    'date' => 'date',
    'start_time' => 'datetime',
    'end_time' => 'datetime',
    'duration' => 'decimal:2',
    'approved_at' => 'datetime',
    'billable' => 'boolean',
    'cost_rate' => 'decimal:2',
    'bill_rate' => 'decimal:2'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function task()
  {
    return $this->belongsTo(Task::class);
  }

  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  public function getTotalCostAttribute()
  {
    return $this->duration * $this->cost_rate;
  }

  public function getTotalBillableAttribute()
  {
    return $this->billable ? ($this->duration * $this->bill_rate) : 0;
  }

  public function getOvertimeHoursAttribute()
  {
    // Assuming standard workday is 8 hours
    $standardHours = 8;
    return max(0, $this->duration - $standardHours);
  }

  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  public function scopePending($query)
  {
    return $query->whereNull('approved_at');
  }

  public function scopeForPeriod($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  public function scopeBillable($query)
  {
    return $query->where('billable', true);
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeForProject($query, $projectId)
  {
    return $query->where('project_id', $projectId);
  }
}
