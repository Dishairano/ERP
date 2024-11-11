<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DashboardCategory extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'sort_order',
    'icon',
    'parent_id'
  ];

  // Relationships
  public function parent()
  {
    return $this->belongsTo(DashboardCategory::class, 'parent_id');
  }

  public function children()
  {
    return $this->hasMany(DashboardCategory::class, 'parent_id');
  }

  public function dashboards()
  {
    return $this->belongsToMany(Dashboard::class, 'dashboard_category_assignments');
  }

  // Scopes
  public function scopeParents($query)
  {
    return $query->whereNull('parent_id');
  }

  public function scopeOrdered($query)
  {
    return $query->orderBy('sort_order')->orderBy('name');
  }

  // Methods
  public function getFullHierarchy()
  {
    $hierarchy = [$this];
    $current = $this;

    while ($current->parent) {
      $hierarchy[] = $current->parent;
      $current = $current->parent;
    }

    return array_reverse($hierarchy);
  }

  public function getAllChildren()
  {
    return $this->children()->with('children')->get()->map(function ($child) {
      return array_merge([$child], $child->getAllChildren()->all());
    })->flatten();
  }

  public function moveToPosition($position)
  {
    $this->sort_order = $position;
    $this->save();

    // Reorder siblings
    static::where('parent_id', $this->parent_id)
      ->where('id', '!=', $this->id)
      ->where('sort_order', '>=', $position)
      ->increment('sort_order');
  }

  public function moveToParent($parentId)
  {
    $oldParentId = $this->parent_id;
    $this->parent_id = $parentId;
    $this->sort_order = static::where('parent_id', $parentId)->max('sort_order') + 1;
    $this->save();

    // Reorder old siblings
    if ($oldParentId) {
      static::where('parent_id', $oldParentId)
        ->where('sort_order', '>', $this->getOriginal('sort_order'))
        ->decrement('sort_order');
    }
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($category) {
      if (empty($category->slug)) {
        $category->slug = Str::slug($category->name);
      }
      if (is_null($category->sort_order)) {
        $category->sort_order = static::where('parent_id', $category->parent_id)
          ->max('sort_order') + 1;
      }
    });

    static::deleting(function ($category) {
      // Handle children when deleting
      if ($category->isForceDeleting()) {
        $category->children()->forceDelete();
      } else {
        $category->children()->delete();
      }
    });
  }
}
