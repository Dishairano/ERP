<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'supplier_id',
    'receipt_number',
    'receipt_date',
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
    'receipt_date' => 'datetime'
  ];

  /**
   * Get the supplier that owns the receipt.
   */
  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  /**
   * Get the user who created the receipt.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get the putaway orders for this receipt.
   */
  public function putawayOrders()
  {
    return $this->hasMany(PutawayOrder::class, 'receipt_id');
  }

  /**
   * Get all of the receipt's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the receipt's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
