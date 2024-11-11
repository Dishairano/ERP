<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'supplier_id',
    'order_date',
    'delivery_date',
    'status',
    'total_amount',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'order_date' => 'date',
    'delivery_date' => 'date',
    'total_amount' => 'decimal:2'
  ];

  /**
   * Get the supplier for this order.
   */
  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  /**
   * Get the items for this order.
   */
  public function items()
  {
    return $this->hasMany(PurchaseOrderItem::class);
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
