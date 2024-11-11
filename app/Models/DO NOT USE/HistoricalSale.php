<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalSale extends Model
{
  protected $fillable = [
    'product_id',
    'region_id',
    'sale_date',
    'quantity_sold',
    'sale_value',
    'customer_segment'
  ];

  protected $casts = [
    'sale_date' => 'date',
    'sale_value' => 'decimal:2'
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function region()
  {
    return $this->belongsTo(Department::class, 'region_id');
  }

  public static function getCustomerSegments()
  {
    return [
      'retail' => 'Retail',
      'wholesale' => 'Wholesale',
      'corporate' => 'Corporate',
      'government' => 'Government',
      'other' => 'Other'
    ];
  }
}
