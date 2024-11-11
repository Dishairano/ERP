<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionalEvent extends Model
{
  protected $fillable = [
    'event_name',
    'description',
    'start_date',
    'end_date',
    'expected_lift',
    'affected_products',
    'affected_regions',
    'budget'
  ];

  protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'expected_lift' => 'decimal:2',
    'affected_products' => 'array',
    'affected_regions' => 'array',
    'budget' => 'decimal:2'
  ];

  public function products()
  {
    return Product::whereIn('id', $this->affected_products);
  }

  public function regions()
  {
    return Department::whereIn('id', $this->affected_regions);
  }
}
