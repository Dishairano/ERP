<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'category_id',
    'location_id',
    'purchase_date',
    'purchase_cost',
    'useful_life',
    'depreciation_method',
    'salvage_value',
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
    'purchase_date' => 'date',
    'purchase_cost' => 'decimal:2',
    'useful_life' => 'integer',
    'salvage_value' => 'decimal:2'
  ];

  /**
   * Get the category for this asset.
   */
  public function category()
  {
    return $this->belongsTo(AssetCategory::class, 'category_id');
  }

  /**
   * Get the location for this asset.
   */
  public function location()
  {
    return $this->belongsTo(Location::class);
  }

  /**
   * Get the user who created the asset.
   */
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  /**
   * Get all of the asset's notes.
   */
  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  /**
   * Get all of the asset's activities.
   */
  public function activities()
  {
    return $this->morphMany(Activity::class, 'activitable');
  }
}
