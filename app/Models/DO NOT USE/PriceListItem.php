<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceListItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'price_list_id',
    'product_id',
    'price',
    'min_quantity',
    'max_quantity',
    'start_date',
    'end_date',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'price' => 'decimal:2',
    'min_quantity' => 'integer',
    'max_quantity' => 'integer',
    'start_date' => 'date',
    'end_date' => 'date'
  ];

  /**
   * Get the price list that owns the item.
   */
  public function priceList()
  {
    return $this->belongsTo(PriceList::class);
  }

  /**
   * Get the product associated with this price list item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
