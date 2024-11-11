<?php

namespace App\Traits;

trait HasLaravelAuthorization
{
  public function can($abilities, $arguments = []): bool
  {
    // If $abilities is an array, check if user has all abilities
    if (is_array($abilities)) {
      foreach ($abilities as $ability) {
        if (!$this->hasPermission($ability)) {
          return false;
        }
      }
      return true;
    }

    // If $abilities is a string, treat it as a single permission
    return $this->hasPermission($abilities);
  }

  public function cannot($abilities, $arguments = []): bool
  {
    return !$this->can($abilities, $arguments);
  }
}
