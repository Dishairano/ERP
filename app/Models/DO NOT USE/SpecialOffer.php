<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialOffer extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'description',
    'discount_type',
    'discount_value',
    'start_date',
    'end_date',
    'usage_limit',
    'minimum_purchase',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'discount_value' => 'decimal:2',
    'usage_limit' => 'integer',
    'minimum_purchase' => 'decimal:2',
    'start_date' => 'date',
    'end_date' => 'date'
  ];

  /**
   * Get the products associated with this special offer.
   */
  public function products()
  {
    return $this->belongsToMany(Product::class);
  }

  /**
   * Get the customers associated with this special offer.
   */
  public function customers()
  {
    return $this->belongsToMany(Customer::class);
  }

  /**
   * Get all of the special offer's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the special offer's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
