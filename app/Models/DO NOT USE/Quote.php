<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'customer_id',
    'valid_until',
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
    'valid_until' => 'datetime',
    'total_amount' => 'decimal:2'
  ];

  /**
   * Get the customer that owns the quote.
   */
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  /**
   * Get the user who created the quote.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the items for this quote.
   */
  public function items()
  {
    return $this->hasMany(QuoteItem::class);
  }

  /**
   * Get all of the quote's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the quote's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
