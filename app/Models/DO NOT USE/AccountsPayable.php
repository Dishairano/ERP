<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountsPayable extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'supplier_id',
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
   * Get the supplier for this payable.
   */
  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  /**
   * Get the invoice for this payable.
   */
  public function invoice()
  {
    return $this->belongsTo(Invoice::class);
  }

  /**
   * Get the user who created the payable.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the payable's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the payable's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
