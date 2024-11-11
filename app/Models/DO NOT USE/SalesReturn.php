<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_id',
    'reason',
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
    'total_amount' => 'decimal:2'
  ];

  /**
   * Get the order that owns the return.
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the user who created the return.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the items for this return.
   */
  public function items()
  {
    return $this->hasMany(SalesReturnItem::class, 'return_id');
  }

  /**
   * Get all of the return's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the return's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
