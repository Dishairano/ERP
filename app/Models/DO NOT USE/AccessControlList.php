<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccessControlList extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'role',
    'resource_type',
    'resource_id',
    'permission_level',
    'expires_at',
    'granted_by'
  ];

  protected $casts = [
    'expires_at' => 'datetime'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function grantedBy()
  {
    return $this->belongsTo(User::class, 'granted_by');
  }

  public function isExpired()
  {
    return $this->expires_at && $this->expires_at->isPast();
  }

  public function hasRole($role)
  {
    if ($this->isExpired()) {
      return false;
    }

    return $this->role === $role;
  }

  public function hasPermission($level)
  {
    if ($this->isExpired()) {
      return false;
    }

    $levels = [
      'read' => 1,
      'write' => 2,
      'delete' => 3,
      'admin' => 4
    ];

    return $levels[$this->permission_level] >= $levels[$level];
  }
}
