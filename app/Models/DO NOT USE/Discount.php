<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'type',
    'value',
    'start_date',
    'end_date',
    'minimum_quantity',
    'minimum_amount',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'value' => 'decimal:2',
    'minimum_quantity' => 'integer',
    'minimum_amount' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date'
  ];

  /**
   * Get the products associated with this discount.
   */
  public function products()
  {
    return $this->belongsToMany(Product::class);
  }

  /**
   * Get the customers associated with this discount.
   */
  public function customers()
  {
    return $this->belongsToMany(Customer::class);
  }

  /**
   * Get all of the discount's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the discount's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
