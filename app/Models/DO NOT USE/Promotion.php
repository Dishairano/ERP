<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
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
    'type',
    'start_date',
    'end_date',
    'reward_type',
    'reward_value',
    'status'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date'
  ];

  /**
   * Get the products associated with this promotion.
   */
  public function products()
  {
    return $this->belongsToMany(Product::class);
  }

  /**
   * Get the conditions for this promotion.
   */
  public function conditions()
  {
    return $this->hasMany(PromotionCondition::class);
  }

  /**
   * Get all of the promotion's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the promotion's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
