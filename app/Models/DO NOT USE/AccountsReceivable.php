<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsReceivable extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'customer_id',
    'invoice_id',
    'amount',
    'due_date',
    'payment_terms',
    'status',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'amount' => 'decimal:2',
    'due_date' => 'date'
  ];

  /**
   * Get the customer for this receivable.
   */
  public function customer()
  {
    return $this->belongsTo(Customer::class);
  }

  /**
   * Get the invoice for this receivable.
   */
  public function invoice()
  {
    return $this->belongsTo(Invoice::class);
  }

  /**
   * Get the user who created the receivable.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the receivable's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the receivable's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
