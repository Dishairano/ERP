<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'customer_id',
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
    'order_date' => 'datetime',
    'delivery_date' => 'datetime',
    'total_amount' => 'decimal:2'
  ];

  /**
   * Get the customer that owns the order.
   */
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  /**
   * Get the user who created the order.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the items for this order.
   */
  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }

  /**
   * Get the invoice for this order.
   */
  public function invoice()
  {
    return $this->hasOne(Invoice::class);
  }

  /**
   * Get the returns for this order.
   */
  public function returns()
  {
    return $this->hasMany(SalesReturn::class);
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
