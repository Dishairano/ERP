<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreFinanceBudgetModal extends Model
{
    use SoftDeletes;

    protected $table = 'finance_budgets';

    protected $fillable = [
        'name',
        'description',
        'fiscal_year',
        'start_date',
        'end_date',
        'total_amount',
        'allocated_amount',
        'remaining_amount',
        'department_id',
        'project_id',
        'status',
        'notes',
        'created_by',
        'approved_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'allocated_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function department()
    {
        return $this->belongsTo(CoreFinanceDepartmentModal::class, 'department_id');
    }

    public function project()
    {
        return $this->belongsTo(CoreProjectModal::class, 'project_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function lineItems()
    {
        return $this->hasMany(CoreFinanceBudgetLineItemModal::class, 'budget_id');
    }

    public function scenarios()
    {
        return $this->hasMany(CoreFinanceBudgetScenarioModal::class, 'budget_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Accessors
    public function getUtilizationPercentageAttribute()
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return round(($this->allocated_amount / $this->total_amount) * 100, 2);
    }

    public function getRemainingPercentageAttribute()
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return round(($this->remaining_amount / $this->total_amount) * 100, 2);
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->remaining_amount < 0;
    }

    public function getIsNearlyDepletedAttribute()
    {
        return $this->remaining_amount > 0 && $this->utilization_percentage >= 90;
    }
}
