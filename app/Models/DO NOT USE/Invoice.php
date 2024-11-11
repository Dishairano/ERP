<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'order_id',
    'invoice_date',
    'due_date',
    'status',
    'total_amount',
    'paid_amount',
    'payment_date',
    'payment_method',
    'payment_reference',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'invoice_date' => 'datetime',
    'due_date' => 'datetime',
    'payment_date' => 'datetime',
    'total_amount' => 'decimal:2',
    'paid_amount' => 'decimal:2'
  ];

  /**
   * Get the order that owns the invoice.
   */
  public function order()
  {
    return $this->belongsTo(Order::class);
  }

  /**
   * Get the user who created the invoice.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the invoice's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the invoice's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
