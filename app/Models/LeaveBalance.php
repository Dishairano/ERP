<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveBalance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'year',
        'total_days',
        'used_days',
        'pending_days',
        'remaining_days',
        'notes'
    ];

    protected $casts = [
        'total_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'pending_days' => 'decimal:2',
        'remaining_days' => 'decimal:2',
        'year' => 'integer'
    ];

    /**
     * Get the user that owns the leave balance.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave type of the balance.
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Calculate and update remaining days.
     */
    public function updateRemainingDays()
    {
        $this->remaining_days = $this->total_days - $this->used_days - $this->pending_days;
        $this->save();
    }

    /**
     * Check if there are enough days available for a request.
     */
    public function hasEnoughDays($requestedDays)
    {
        return $this->remaining_days >= $requestedDays;
    }

    /**
     * Add pending days when a leave request is submitted.
     */
    public function addPendingDays($days)
    {
        $this->pending_days += $days;
        $this->updateRemainingDays();
    }

    /**
     * Remove pending days when a leave request is approved/rejected.
     */
    public function removePendingDays($days)
    {
        $this->pending_days = max(0, $this->pending_days - $days);
        $this->updateRemainingDays();
    }

    /**
     * Add used days when a leave request is approved.
     */
    public function addUsedDays($days)
    {
        $this->used_days += $days;
        $this->updateRemainingDays();
    }

    /**
     * Remove used days when a leave request is cancelled.
     */
    public function removeUsedDays($days)
    {
        $this->used_days = max(0, $this->used_days - $days);
        $this->updateRemainingDays();
    }

    /**
     * Carry forward remaining days to next year.
     */
    public function carryForward()
    {
        if (!$this->leaveType->allow_carry_forward) {
            return null;
        }

        $nextYear = $this->year + 1;
        $daysToCarry = $this->remaining_days;

        // Apply max carry forward limit if set
        if ($this->leaveType->max_carry_forward_days) {
            $daysToCarry = min($daysToCarry, $this->leaveType->max_carry_forward_days);
        }

        // Create or update next year's balance
        return self::updateOrCreate(
            [
                'user_id' => $this->user_id,
                'leave_type_id' => $this->leave_type_id,
                'year' => $nextYear
            ],
            [
                'total_days' => $this->leaveType->days_per_year + $daysToCarry,
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => $this->leaveType->days_per_year + $daysToCarry,
                'notes' => "Includes {$daysToCarry} days carried forward from {$this->year}"
            ]
        );
    }

    /**
     * Get the balance for a specific user, leave type and year.
     */
    public static function getBalance($userId, $leaveTypeId, $year)
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'leave_type_id' => $leaveTypeId,
                'year' => $year
            ],
            [
                'total_days' => LeaveType::find($leaveTypeId)->days_per_year,
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => LeaveType::find($leaveTypeId)->days_per_year
            ]
        );
    }
}
