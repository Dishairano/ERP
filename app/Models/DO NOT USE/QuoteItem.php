<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'quote_id',
    'product_id',
    'quantity',
    'price',
    'subtotal'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'price' => 'decimal:2',
    'subtotal' => 'decimal:2'
  ];

  /**
   * Get the quote that owns the item.
   */
  public function quote()
  {
    return $this->belongsTo(Quote::class);
  }

  /**
   * Get the product for this item.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
