<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class CoreFinancePresetDepartmentModal extends Model
{
  use HasFactory;

  protected $table = 'finance_preset_departments';

  protected $fillable = [
    'name',
    'department_id',
    'fiscal_year',
    'total_amount',
    'description',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'notes'
  ];

  protected $casts = [
    'total_amount' => 'decimal:2',
    'approved_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime'
  ];

  /**
   * Get the user who created the preset.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved the preset.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the department that owns the preset.
   */
  public function department(): BelongsTo
  {
    return $this->belongsTo(CoreFinanceDepartmentModal::class, 'department_id');
  }

  /**
   * Get the line items for the preset.
   */
  public function lineItems(): HasMany
  {
    return $this->hasMany(CoreFinancePresetLineItemModal::class, 'preset_id');
  }

  /**
   * Check if the preset is approved.
   */
  public function isApproved(): bool
  {
    return !is_null($this->approved_at);
  }

  /**
   * Check if the preset is active.
   */
  public function isActive(): bool
  {
    return $this->status === 'active';
  }

  /**
   * Create a new budget from this preset.
   */
  public function createBudget(): CoreFinanceBudgetModal
  {
    $budget = CoreFinanceBudgetModal::create([
      'name' => $this->name,
      'description' => $this->description,
      'fiscal_year' => $this->fiscal_year,
      'total_amount' => $this->total_amount,
      'department_id' => $this->department_id,
      'created_by' => Auth::id(),
      'status' => 'draft',
      'notes' => "Created from preset: {$this->name}"
    ]);

    // Copy line items
    foreach ($this->lineItems as $lineItem) {
      $budget->lineItems()->create([
        'name' => $lineItem->name,
        'description' => $lineItem->description,
        'amount' => $lineItem->amount,
        'category' => $lineItem->category,
        'notes' => $lineItem->notes
      ]);
    }

    return $budget;
  }

  /**
   * Update the total amount based on line items.
   */
  public function updateTotalAmount(): void
  {
    $this->total_amount = $this->lineItems()->sum('amount');
    $this->save();
  }

  /**
   * Format the total amount as currency.
   */
  public function getFormattedTotalAmount(): string
  {
    return number_format($this->total_amount, 2);
  }

  /**
   * Scope a query to only include presets for a specific fiscal year.
   */
  public function scopeForFiscalYear($query, $year)
  {
    return $query->where('fiscal_year', $year);
  }

  /**
   * Scope a query to only include active presets.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to only include presets for a specific department.
   */
  public function scopeForDepartment($query, $departmentId)
  {
    return $query->where('department_id', $departmentId);
  }
}
