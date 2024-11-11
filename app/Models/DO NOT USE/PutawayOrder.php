<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PutawayOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'receipt_id',
    'warehouse_id',
    'priority',
    'status',
    'handler_id',
    'completed_at',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'completed_at' => 'datetime'
  ];

  /**
   * Get the goods receipt associated with this putaway order.
   */
  public function receipt()
  {
    return $this->belongsTo(GoodsReceipt::class, 'receipt_id');
  }

  /**
   * Get the warehouse for this putaway order.
   */
  public function warehouse()
  {
    return $this->belongsTo(Warehouse::class);
  }

  /**
   * Get the items for this putaway order.
   */
  public function items()
  {
    return $this->hasMany(PutawayOrderItem::class);
  }

  /**
   * Get the handler assigned to this order.
   */
  public function handler()
  {
    return $this->belongsTo(User::class, 'handler_id');
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
