<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    /**
     * Determine if the user can view any leave requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_leave_requests');
    }

    /**
     * Determine if the user can view the leave request.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('view_leave_requests') &&
            ($user->id === $leaveRequest->user_id || $user->hasPermission('view_all_leave_requests'));
    }

    /**
     * Determine if the user can create leave requests.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_leave_requests');
    }

    /**
     * Determine if the user can update the leave request.
     */
    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('edit_leave_requests') &&
            $user->id === $leaveRequest->user_id &&
            $leaveRequest->status === 'draft';
    }

    /**
     * Determine if the user can delete the leave request.
     */
    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('delete_leave_requests') &&
            $user->id === $leaveRequest->user_id &&
            $leaveRequest->status === 'draft';
    }

    /**
     * Determine if the user can submit the leave request.
     */
    public function submit(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('create_leave_requests') &&
            $user->id === $leaveRequest->user_id &&
            $leaveRequest->status === 'draft';
    }

    /**
     * Determine if the user can approve leave requests.
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('approve_leave_requests') &&
            $user->id !== $leaveRequest->user_id &&
            $leaveRequest->status === 'submitted';
    }

    /**
     * Determine if the user can reject leave requests.
     */
    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->hasPermission('approve_leave_requests') &&
            $user->id !== $leaveRequest->user_id &&
            $leaveRequest->status === 'submitted';
    }

    /**
     * Determine if the user can view leave balances.
     */
    public function viewBalances(User $user): bool
    {
        return $user->hasPermission('view_leave_requests');
    }

    /**
     * Determine if the user can view all leave balances.
     */
    public function viewAllBalances(User $user): bool
    {
        return $user->hasPermission('view_all_leave_requests');
    }
}
