<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomComponent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'bill_of_material_id',
    'component_id',
    'quantity',
    'unit',
    'position',
    'notes',
    'is_critical',
    'lead_time',
    'waste_percentage'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:4',
    'is_critical' => 'boolean',
    'lead_time' => 'integer',
    'waste_percentage' => 'decimal:2'
  ];

  /**
   * Get the bill of material that owns the component.
   */
  public function billOfMaterial()
  {
    return $this->belongsTo(BillOfMaterial::class);
  }

  /**
   * Get the product that is used as a component.
   */
  public function component()
  {
    return $this->belongsTo(Product::class, 'component_id');
  }
}
