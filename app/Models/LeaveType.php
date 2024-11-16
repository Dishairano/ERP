<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'days_per_year',
        'requires_approval',
        'paid',
        'allow_carry_forward',
        'max_carry_forward_days'
    ];

    protected $casts = [
        'days_per_year' => 'integer',
        'requires_approval' => 'boolean',
        'paid' => 'boolean',
        'allow_carry_forward' => 'boolean',
        'max_carry_forward_days' => 'decimal:2'
    ];

    /**
     * Get the leave requests for this type.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the leave balances for this type.
     */
    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Check if this leave type allows carry forward.
     */
    public function allowsCarryForward(): bool
    {
        return $this->allow_carry_forward;
    }

    /**
     * Get the maximum days that can be carried forward.
     */
    public function getMaxCarryForwardDays(): ?float
    {
        return $this->max_carry_forward_days;
    }

    /**
     * Check if this leave type requires approval.
     */
    public function requiresApproval(): bool
    {
        return $this->requires_approval;
    }

    /**
     * Check if this is a paid leave type.
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    /**
     * Get the annual quota for this leave type.
     */
    public function getAnnualQuota(): int
    {
        return $this->days_per_year;
    }

    /**
     * Get the formatted name with code.
     */
    public function getFullName(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /**
     * Get the formatted quota description.
     */
    public function getQuotaDescription(): string
    {
        $description = "{$this->days_per_year} days per year";
        if ($this->allow_carry_forward) {
            $description .= ", carry forward allowed";
            if ($this->max_carry_forward_days) {
                $description .= " (max {$this->max_carry_forward_days} days)";
            }
        }
        return $description;
    }

    /**
     * Get the formatted status description.
     */
    public function getStatusDescription(): string
    {
        $status = [];
        if ($this->paid) {
            $status[] = 'Paid';
        } else {
            $status[] = 'Unpaid';
        }
        if ($this->requires_approval) {
            $status[] = 'Requires Approval';
        }
        return implode(' | ', $status);
    }
}
