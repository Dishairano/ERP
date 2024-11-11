<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierEvaluation extends Model
{
  use HasFactory;

  protected $fillable = [
    'supplier_id',
    'user_id',
    'delivery_time_rating',
    'quality_rating',
    'communication_rating',
    'price_rating',
    'overall_rating',
    'comments',
    'order_reference',
    'evaluation_date'
  ];

  protected $casts = [
    'evaluation_date' => 'date',
    'overall_rating' => 'decimal:2',
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  protected static function boot()
  {
    parent::boot();

    static::saving(function ($evaluation) {
      // Calculate overall rating if not set
      if (!$evaluation->overall_rating) {
        $ratings = [
          $evaluation->delivery_time_rating,
          $evaluation->quality_rating,
          $evaluation->communication_rating,
          $evaluation->price_rating
        ];
        $validRatings = array_filter($ratings, function ($rating) {
          return !is_null($rating);
        });

        $evaluation->overall_rating = count($validRatings) > 0
          ? array_sum($validRatings) / count($validRatings)
          : 0;
      }
    });
  }
}
