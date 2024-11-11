<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickingOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_id',
    'warehouse_id',
    'priority',
    'status',
    'picker_id',
    'picked_at',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'picked_at' => 'datetime'
  ];

  /**
   * Get the order associated with this picking order.
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the warehouse for this picking order.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the items for this picking order.
   */
  public function items()
  {
    return $this->hasMany(PickingOrderItem::class);
  }

  /**
   * Get the picker assigned to this order.
   */
  public function picker()
  {
    return $this->belongsTo(User::class, 'picker_id');
  }

  /**
   * Get the user who created the order.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the order's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the order's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
