<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
  protected $fillable = [
    'budget_id',
    'description',
    'amount',
    'status',
    'created_by',
    'approved_by',
    'approved_at',
    'rejection_reason'
  ];

  protected $casts = [
    'amount' => 'decimal:2',
    'approved_at' => 'datetime',
  ];

  /**
   * Get the budget that owns the expense.
   */
  public function budget(): BelongsTo
  {
    return $this->belongsTo(Budget::class);
  }

  /**
   * Get the user who created the expense.
   */
  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the user who approved/rejected the expense.
   */
  public function approver(): BelongsTo
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Scope a query to only include pending expenses.
   */
  public function scopePending($query)
  {
    return $query->where('status', 'pending');
  }

  /**
   * Scope a query to only include approved expenses.
   */
  public function scopeApproved($query)
  {
    return $query->where('status', 'approved');
  }

  /**
   * Scope a query to only include rejected expenses.
   */
  public function scopeRejected($query)
  {
    return $query->where('status', 'rejected');
  }
}
