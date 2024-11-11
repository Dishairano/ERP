<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialRequirement extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'product_id',
    'work_order_id',
    'quantity',
    'required_date',
    'source',
    'status',
    'notes',
    'created_by'
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'quantity' => 'decimal:2',
    'required_date' => 'date'
  ];

  /**
   * Get the product associated with this requirement.
   */
  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Get the work order associated with this requirement.
   */
  public function workOrder()
  {
    return $this->belongsTo(ProductionOrder::class, 'work_order_id');
  }

  /**
   * Get the user who created the requirement.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the requirement's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the requirement's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
