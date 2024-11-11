<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierProduct extends Model
{
  use HasFactory;

  protected $fillable = [
    'supplier_id',
    'product_code',
    'name',
    'description',
    'category',
    'unit_price',
    'currency',
    'minimum_order_quantity',
    'lead_time_days',
    'is_active'
  ];

  protected $casts = [
    'unit_price' => 'decimal:2',
    'is_active' => 'boolean',
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class);
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeCategory($query, $category)
  {
    return $query->where('category', $category);
  }
}
