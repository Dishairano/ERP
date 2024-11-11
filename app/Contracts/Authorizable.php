<?php

namespace App\Contracts;

interface Authorizable
{
  public function hasRole(string $role): bool;
  public function hasPermission(string $permission): bool;
}
