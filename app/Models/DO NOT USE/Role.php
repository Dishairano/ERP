<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'permissions',
  ];

  protected $casts = [
    'permissions' => 'array',
  ];

  public function users()
  {
    return $this->hasMany(User::class);
  }

  /**
   * Check if the role has a specific permission
   */
  public function hasPermission($permission)
  {
    if (in_array('*', $this->permissions ?? [])) {
      return true;
    }

    return in_array($permission, $this->permissions ?? []);
  }

  /**
   * Check if the role has any of the given permissions
   */
  public function hasAnyPermission(array $permissions)
  {
    foreach ($permissions as $permission) {
      if ($this->hasPermission($permission)) {
        return true;
      }
    }
    return false;
  }

  /**
   * Check if the role has all of the given permissions
   */
  public function hasAllPermissions(array $permissions)
  {
    foreach ($permissions as $permission) {
      if (!$this->hasPermission($permission)) {
        return false;
      }
    }
    return true;
  }
}
