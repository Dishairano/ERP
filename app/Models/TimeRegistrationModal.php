<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeRegistrationModal extends Model
{
    use SoftDeletes;

    protected $table = 'time_registrations';

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'date',
        'start_time',
        'end_time',
        'hours',
        'description',
        'billable',
        'overtime',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'hours' => 'decimal:2',
        'billable' => 'boolean',
        'overtime' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(CoreProjectModal::class, 'project_id');
    }

    public function task()
    {
        return $this->belongsTo(CoreProjectTaskModal::class, 'task_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
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

    // Methods
    public function calculateHours()
    {
        if ($this->start_time && $this->end_time) {
            $start = \Carbon\Carbon::parse($this->start_time);
            $end = \Carbon\Carbon::parse($this->end_time);
            $this->hours = $end->diffInMinutes($start) / 60;
        }
    }
}
