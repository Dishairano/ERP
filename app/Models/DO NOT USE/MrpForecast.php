<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrpForecast extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'period',
    'quantity',
    'confidence',
    'method',
    'parameters',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'confidence' => 'decimal:2',
    'parameters' => 'array'
  ];

  /**
   * Get the product associated with this forecast.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the user who created the forecast.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the forecast's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the forecast's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
