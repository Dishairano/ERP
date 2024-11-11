<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
  protected $fillable = [
    'name',
    'code',
    'description',
    'parent_id',
    'status'
  ];

  protected $casts = [
    'status' => 'boolean'
  ];

  public function parent()
  {
    return $this->belongsTo(ItemCategory::class, 'parent_id');
  }

  public function children()
  {
    return $this->hasMany(ItemCategory::class, 'parent_id');
  }

  public function items()
  {
    return $this->hasMany(Item::class, 'category_id');
  }

  public function scopeActive($query)
  {
    return $query->where('status', true);
  }

  public function scopeParents($query)
  {
    return $query->whereNull('parent_id');
  }

  public function getFullPathAttribute()
  {
    $path = [$this->name];
    $category = $this;

    while ($category->parent) {
      $category = $category->parent;
      array_unshift($path, $category->name);
    }

    return implode(' > ', $path);
  }
}
