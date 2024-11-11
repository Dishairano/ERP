<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'version',
    'description',
    'effective_date',
    'status',
    'approved_by',
    'approved_at',
    'notes'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'effective_date' => 'date',
    'approved_at' => 'datetime'
  ];

  /**
   * Get the product that this BOM belongs to.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the components of this BOM.
   */
  public function components()
  {
    return $this->hasMany(BomComponent::class);
  }

  /**
   * Get the production orders using this BOM.
   */
  public function productionOrders()
  {
    return $this->hasMany(ProductionOrder::class);
  }

  /**
   * Get the user who approved this BOM.
   */
  public function approver()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }
}
