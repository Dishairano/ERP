<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoreProjectTimeEntryModal extends Model
{
  use SoftDeletes;

  protected $table = 'project_time_entries';

  protected $fillable = [
    'project_id',
    'user_id',
    'date',
    'hours',
    'description',
    'activity_type',
    'billable_hours',
    'rate'
  ];

  protected $casts = [
    'date' => 'date',
    'hours' => 'decimal:2',
    'billable_hours' => 'decimal:2',
    'rate' => 'decimal:2'
  ];

  // Relationships
  public function project(): BelongsTo
  {
    return $this->belongsTo(CoreProjectModal::class, 'project_id');
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  // Scopes
  public function scopeForProject($query, $projectId)
  {
    return $query->where('project_id', $projectId);
  }

  public function scopeForUser($query, $userId)
  {
    return $query->where('user_id', $userId);
  }

  public function scopeForDate($query, $date)
  {
    return $query->whereDate('date', $date);
  }

  public function scopeForDateRange($query, $startDate, $endDate)
  {
    return $query->whereBetween('date', [$startDate, $endDate]);
  }

  public function scopeBillable($query)
  {
    return $query->where('billable_hours', '>', 0);
  }

  public function scopeByActivityType($query, $type)
  {
    return $query->where('activity_type', $type);
  }

  // Accessors & Mutators
  public function getBillableAmountAttribute(): float
  {
    return $this->billable_hours * ($this->rate ?? 0);
  }

  public function getNonBillableHoursAttribute(): float
  {
    return $this->hours - $this->billable_hours;
  }

  public function getActivityTypeDisplayAttribute(): string
  {
    return ucwords(str_replace('_', ' ', $this->activity_type));
  }

  // Methods
  public function markAsBillable(float $hours = null, float $rate = null): bool
  {
    $data = ['billable_hours' => $hours ?? $this->hours];

    if ($rate !== null) {
      $data['rate'] = $rate;
    }

    return $this->update($data);
  }

  public function markAsNonBillable(): bool
  {
    return $this->update([
      'billable_hours' => 0,
      'rate' => null
    ]);
  }

  public function updateBillableHours(float $hours): bool
  {
    if ($hours > $this->hours) {
      return false;
    }

    return $this->update(['billable_hours' => $hours]);
  }

  public function updateRate(float $rate): bool
  {
    return $this->update(['rate' => $rate]);
  }

  // Statistics Methods
  public static function getTotalHoursForProject($projectId, $startDate = null, $endDate = null): float
  {
    $query = self::forProject($projectId);

    if ($startDate && $endDate) {
      $query->forDateRange($startDate, $endDate);
    }

    return $query->sum('hours');
  }

  public static function getTotalBillableHoursForProject($projectId, $startDate = null, $endDate = null): float
  {
    $query = self::forProject($projectId);

    if ($startDate && $endDate) {
      $query->forDateRange($startDate, $endDate);
    }

    return $query->sum('billable_hours');
  }

  public static function getTotalBillableAmountForProject($projectId, $startDate = null, $endDate = null): float
  {
    $entries = self::forProject($projectId)
      ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
        return $query->forDateRange($startDate, $endDate);
      })
      ->get();

    return $entries->sum(function ($entry) {
      return $entry->billable_hours * ($entry->rate ?? 0);
    });
  }

  public static function getHoursByActivityType($projectId, $startDate = null, $endDate = null): array
  {
    $query = self::forProject($projectId)
      ->selectRaw('activity_type, sum(hours) as total_hours')
      ->groupBy('activity_type');

    if ($startDate && $endDate) {
      $query->forDateRange($startDate, $endDate);
    }

    return $query->pluck('total_hours', 'activity_type')->toArray();
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($model) {
      // Ensure billable hours don't exceed total hours
      if ($model->billable_hours > $model->hours) {
        $model->billable_hours = $model->hours;
      }
    });
  }
}
