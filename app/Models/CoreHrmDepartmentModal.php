<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreHrmDepartmentModal extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'hrm_departments';

  protected $fillable = [
    'name',
    'code',
    'description',
    'manager_id',
    'parent_department_id',
    'level',
    'is_active'
  ];

  protected $casts = [
    'level' => 'integer',
    'is_active' => 'boolean'
  ];

  public function manager()
  {
    return $this->belongsTo(User::class, 'manager_id');
  }

  public function parentDepartment()
  {
    return $this->belongsTo(CoreHrmDepartmentModal::class, 'parent_department_id');
  }

  public function childDepartments()
  {
    return $this->hasMany(CoreHrmDepartmentModal::class, 'parent_department_id');
  }

  public function employees()
  {
    return $this->hasMany(CoreHrmEmployeeModal::class, 'department_id');
  }

  public function jobPostings()
  {
    return $this->hasMany(CoreHrmJobPostingModal::class, 'department');
  }

  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  public function scopeTopLevel($query)
  {
    return $query->whereNull('parent_department_id');
  }

  public function scopeByLevel($query, $level)
  {
    return $query->where('level', $level);
  }

  public function getAllChildDepartments()
  {
    $children = collect();

    foreach ($this->childDepartments as $child) {
      $children->push($child);
      $children = $children->merge($child->getAllChildDepartments());
    }

    return $children;
  }

  public function getAllParentDepartments()
  {
    $parents = collect();
    $current = $this->parentDepartment;

    while ($current) {
      $parents->push($current);
      $current = $current->parentDepartment;
    }

    return $parents->reverse();
  }

  public function getEmployeeCount()
  {
    return $this->employees()->count();
  }

  public function getTotalEmployeeCount()
  {
    $count = $this->getEmployeeCount();

    foreach ($this->childDepartments as $child) {
      $count += $child->getTotalEmployeeCount();
    }

    return $count;
  }

  public function getHierarchyPathAttribute()
  {
    $path = $this->getAllParentDepartments()->pluck('name')->push($this->name);
    return $path->implode(' > ');
  }
}
