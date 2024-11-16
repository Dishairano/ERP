<?php

namespace App\Helpers;

class LeaveRequestHelper
{
    /**
     * Get the CSS class for a leave request status.
     */
    public static function getStatusClass(string $status): string
    {
        return match($status) {
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            'submitted' => 'badge-warning',
            'draft' => 'badge-secondary',
            default => 'badge-info'
        };
    }
}
