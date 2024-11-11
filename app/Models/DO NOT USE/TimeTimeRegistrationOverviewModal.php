<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeTimeRegistrationOverviewModal extends Model
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

  public function scopeForPeriod($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeForProject($query, $projectId)
  {
    return $query->where('project_id', $projectId);
  }

  public function scopeApproved($query)
  {
    return $query->whereNotNull('approved_at');
  }

  public function scopePending($query)
  {
    return $query->whereNull('approved_at');
  }

  public function scopeForCurrentWeek($query)
  {
    return $query->whereBetween('date', [
      Carbon::now()->startOfWeek(),
      Carbon::now()->endOfWeek()
    ]);
  }

  public function scopeForCurrentMonth($query)
  {
    return $query->whereBetween('date', [
      Carbon::now()->startOfMonth(),
      Carbon::now()->endOfMonth()
    ]);
  }

  public function scopeBillable($query)
  {
    return $query->where('billable', true);
  }

  public function getFormattedDurationAttribute()
  {
    $hours = floor($this->duration);
    $minutes = round(($this->duration - $hours) * 60);
    return sprintf('%02d:%02d', $hours, $minutes);
  }

  public function getFormattedCostAttribute()
  {
    return number_format($this->duration * $this->cost_rate, 2);
  }

  public function getFormattedBillableAmountAttribute()
  {
    return $this->billable ? number_format($this->duration * $this->bill_rate, 2) : '0.00';
  }

  public function getStatusColorAttribute()
  {
    return match ($this->status) {
      'approved' => 'success',
      'rejected' => 'danger',
      'pending' => 'warning',
      default => 'secondary'
    };
  }

  public function isEditable()
  {
    return $this->status === 'pending' ||
      $this->status === 'rejected' ||
      Carbon::parse($this->date)->isToday();
  }

  public function isDeletable()
  {
    return $this->status !== 'approved' &&
      Carbon::parse($this->date)->diffInDays(now()) <= 7;
  }
}
