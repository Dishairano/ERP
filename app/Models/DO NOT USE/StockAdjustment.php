<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'warehouse_id',
    'type',
    'quantity',
    'reason',
    'notes',
    'status',
    'requested_by',
    'approved_by',
    'approved_at'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'approved_at' => 'datetime'
  ];

  /**
   * Get the product being adjusted.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the warehouse where the adjustment is made.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the user who requested the adjustment.
   */
  public function requester()
  {
    return $this->belongsTo(User::class, 'requested_by');
  }

  /**
   * Get the user who approved the adjustment.
   */
  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  /**
   * Get the stock movement created by this adjustment.
   */
  public function stockMovement()
  {
    return $this->morphOne(StockMovement::class, 'reference');
  }

  /**
   * Get all of the adjustment's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the adjustment's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
