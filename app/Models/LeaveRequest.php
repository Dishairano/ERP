<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Contracts\Authorizable;

class LeaveRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime'
    ];

    /**
     * Get the user that owns the leave request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave type of the request.
     */
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    /**
     * Get the user who approved the request.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate the number of days.
     */
    public function getDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if the leave request is pending.
     */
    public function isPending()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if the leave request is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the leave request is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if the leave request is in draft status.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the leave request can be edited.
     */
    public function canEdit()
    {
        return $this->isDraft() && Auth::check() && $this->user_id === Auth::id();
    }

    /**
     * Check if the leave request can be deleted.
     */
    public function canDelete()
    {
        return $this->isDraft() && Auth::check() && $this->user_id === Auth::id();
    }

    /**
     * Check if the leave request can be submitted.
     */
    public function canSubmit()
    {
        return $this->isDraft() && Auth::check() && $this->user_id === Auth::id();
    }

    /**
     * Check if the leave request can be approved/rejected.
     */
    public function canApprove()
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var Authorizable $user */
        $user = Auth::user();
        return $this->isPending() && $user->hasPermission('approve_leave_requests');
    }
}
