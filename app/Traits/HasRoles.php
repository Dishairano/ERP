<?php

namespace App\Traits;

use App\Models\User;

trait HasRoles
{
  public function hasRole($role): bool
  {
    if (is_array($role)) {
      return in_array($this->role, $role);
    }
    return $this->role === $role;
  }

  public function hasAnyRole($roles): bool
  {
    if (is_array($roles)) {
      return in_array($this->role, $roles);
    }
    return in_array($this->role, explode('|', $roles));
  }

  public function hasAllRoles($roles): bool
  {
    if (is_array($roles)) {
      return !array_diff($roles, [$this->role]);
    }
    $roles = explode('|', $roles);
    return !array_diff($roles, [$this->role]);
  }

  public function isAdmin(): bool
  {
    return $this->role === User::ROLE_ADMIN;
  }

  public function isManager(): bool
  {
    return $this->role === User::ROLE_MANAGER;
  }

  public function isEmployee(): bool
  {
    return $this->role === User::ROLE_EMPLOYEE;
  }
}
