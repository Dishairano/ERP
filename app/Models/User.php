<?php

namespace App\Models;

use App\Contracts\Authorizable;
use App\Traits\HasUserFeatures;
use App\Traits\HasAuthorization;
use App\Traits\HasLaravelAuthorization;
use App\Traits\HasDashboardPreferences;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class User extends Authenticatable implements Authorizable
{
    use Notifiable,
        SoftDeletes,
        HasUserFeatures,
        HasAuthorization,
        HasLaravelAuthorization,
        HasDashboardPreferences;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'hourly_rate',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'hourly_rate' => 'decimal:2'
    ];

    /**
     * Get the tasks assigned to the user.
     */
    public function tasks()
    {
        return $this->hasMany(CoreProjectTaskModal::class, 'assignee_id');
    }

    /**
     * Get the time registrations for the user.
     */
    public function timeRegistrations()
    {
        return $this->hasMany(TimeRegistrationModal::class);
    }

    /**
     * Get the projects managed by the user.
     */
    public function managedProjects()
    {
        return $this->hasMany(CoreProjectModal::class, 'manager_id');
    }

    /**
     * Get the leave requests submitted by the user.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the leave requests approved by the user.
     */
    public function approvedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    /**
     * Get the leave balances for the user.
     */
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Get all leave balances for the current year.
     *
     * @return Collection
     */
    public function getCurrentLeaveBalances(): Collection
    {
        $year = Carbon::now()->year;
        $leaveTypes = LeaveType::all();
        $balances = collect();

        foreach ($leaveTypes as $leaveType) {
            $balance = LeaveBalance::firstOrCreate(
                [
                    'user_id' => $this->id,
                    'leave_type_id' => $leaveType->id,
                    'year' => $year
                ],
                [
                    'total_days' => $leaveType->days_per_year,
                    'used_days' => 0,
                    'pending_days' => 0,
                    'remaining_days' => $leaveType->days_per_year
                ]
            );

            $balances->push([
                'type' => $leaveType,
                'balance' => $balance
            ]);
        }

        return $balances;
    }

    /**
     * Get the leave balance for a specific leave type and year.
     */
    public function getLeaveBalance($leaveTypeId, $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $leaveType = LeaveType::findOrFail($leaveTypeId);

        return LeaveBalance::firstOrCreate(
            [
                'user_id' => $this->id,
                'leave_type_id' => $leaveTypeId,
                'year' => $year
            ],
            [
                'total_days' => $leaveType->days_per_year,
                'used_days' => 0,
                'pending_days' => 0,
                'remaining_days' => $leaveType->days_per_year
            ]
        );
    }

    /**
     * Check if user has enough leave balance for a request.
     */
    public function hasEnoughLeaveBalance($leaveTypeId, $days, $year = null): bool
    {
        $balance = $this->getLeaveBalance($leaveTypeId, $year);
        return $balance->remaining_days >= $days;
    }

    /**
     * Get pending leave requests.
     */
    public function getPendingLeaveRequests(): Collection
    {
        return $this->leaveRequests()
            ->where('status', 'submitted')
            ->with(['leaveType'])
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get approved leave requests.
     */
    public function getApprovedLeaveRequests($year = null): Collection
    {
        $query = $this->leaveRequests()
            ->where('status', 'approved')
            ->with(['leaveType']);

        if ($year) {
            $query->whereYear('start_date', $year);
        }

        return $query->orderBy('start_date')->get();
    }

    /**
     * Get leave requests that need approval.
     */
    public function getLeaveRequestsForApproval(): Collection
    {
        if (!$this->hasPermission('approve_leave_requests')) {
            return collect();
        }

        return LeaveRequest::where('status', 'submitted')
            ->with(['user', 'leaveType'])
            ->orderBy('start_date')
            ->get();
    }
}
