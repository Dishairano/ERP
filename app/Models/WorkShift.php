<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class WorkShift extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'start_time',
        'end_time',
        'location',
        'notes',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the user that owns the shift.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the duration of the shift in hours.
     */
    public function getDurationAttribute(): float
    {
        return $this->start_time->diffInHours($this->end_time);
    }

    /**
     * Check if the shift is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_time, $this->end_time);
    }

    /**
     * Check if the shift is upcoming.
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_time->isFuture();
    }

    /**
     * Check if the shift is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->end_time->isPast();
    }

    /**
     * Get the shift type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'morning' => 'Morning Shift',
            'afternoon' => 'Afternoon Shift',
            'evening' => 'Evening Shift',
            'night' => 'Night Shift',
            default => ucfirst($this->type)
        };
    }

    /**
     * Get shifts for a specific date range.
     */
    public static function getShiftsForRange(Carbon $start, Carbon $end, ?int $userId = null)
    {
        $query = self::whereBetween('start_time', [$start, $end])
            ->orWhereBetween('end_time', [$start, $end])
            ->with(['user']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->orderBy('start_time')->get();
    }

    /**
     * Get upcoming shifts.
     */
    public static function getUpcomingShifts(int $limit = 10, ?int $userId = null)
    {
        $query = self::where('start_time', '>=', now())
            ->with(['user'])
            ->orderBy('start_time');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Get shifts that overlap with the given time range.
     */
    public static function findOverlappingShifts(Carbon $start, Carbon $end, ?int $userId = null, ?int $excludeShiftId = null)
    {
        $query = self::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_time', [$start, $end])
                ->orWhereBetween('end_time', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                });
        });

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($excludeShiftId) {
            $query->where('id', '!=', $excludeShiftId);
        }

        return $query->get();
    }
}
