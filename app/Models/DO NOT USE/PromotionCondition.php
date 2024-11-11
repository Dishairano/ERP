<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCondition extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'promotion_id',
    'type',
    'operator',
    'value',
    'target_type',
    'target_id',
    'priority'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'value' => 'decimal:2',
    'priority' => 'integer'
  ];

  /**
   * Get the promotion that owns the condition.
   */
  public function promotion()
  {
    return $this->belongsTo(Promotion::class);
  }

  /**
   * Get the target model (polymorphic).
   */
  public function target()
  {
    return $this->morphTo();
  }
}
