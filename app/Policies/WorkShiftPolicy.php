<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkShift;

class WorkShiftPolicy
{
    /**
     * Determine if the user can view any shifts.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_shifts');
    }

    /**
     * Determine if the user can view the shift.
     */
    public function view(User $user, WorkShift $shift): bool
    {
        return $user->hasPermission('view_shifts') &&
            ($user->id === $shift->user_id || $user->hasPermission('view_all_shifts'));
    }

    /**
     * Determine if the user can create shifts.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create_shifts');
    }

    /**
     * Determine if the user can update the shift.
     */
    public function update(User $user, WorkShift $shift): bool
    {
        if (!$user->hasPermission('edit_shifts')) {
            return false;
        }

        // Only allow updates to scheduled shifts
        if ($shift->status !== 'scheduled') {
            return false;
        }

        // Managers can edit any shift, users can only edit their own
        return $user->hasPermission('manage_shifts') || $user->id === $shift->user_id;
    }

    /**
     * Determine if the user can delete the shift.
     */
    public function delete(User $user, WorkShift $shift): bool
    {
        if (!$user->hasPermission('delete_shifts')) {
            return false;
        }

        // Only allow deletion of scheduled shifts
        if ($shift->status !== 'scheduled') {
            return false;
        }

        // Managers can delete any shift, users can only delete their own
        return $user->hasPermission('manage_shifts') || $user->id === $shift->user_id;
    }

    /**
     * Determine if the user can manage all shifts.
     */
    public function manageAll(User $user): bool
    {
        return $user->hasPermission('manage_shifts');
    }

    /**
     * Determine if the user can view all shifts.
     */
    public function viewAll(User $user): bool
    {
        return $user->hasPermission('view_all_shifts');
    }

    /**
     * Determine if the user can start the shift.
     */
    public function start(User $user, WorkShift $shift): bool
    {
        if ($shift->status !== 'scheduled') {
            return false;
        }

        return $user->id === $shift->user_id || $user->hasPermission('manage_shifts');
    }

    /**
     * Determine if the user can complete the shift.
     */
    public function complete(User $user, WorkShift $shift): bool
    {
        if ($shift->status !== 'in_progress') {
            return false;
        }

        return $user->id === $shift->user_id || $user->hasPermission('manage_shifts');
    }

    /**
     * Determine if the user can cancel the shift.
     */
    public function cancel(User $user, WorkShift $shift): bool
    {
        if (!in_array($shift->status, ['scheduled', 'in_progress'])) {
            return false;
        }

        return $user->id === $shift->user_id || $user->hasPermission('manage_shifts');
    }
}
