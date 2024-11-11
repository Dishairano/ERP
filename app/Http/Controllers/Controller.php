<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
  use AuthorizesRequests, ValidatesRequests;

  protected function authorize(string $permission)
  {
    /** @var User|null $user */
    $user = Auth::user();
    if (!$user || !$user->can($permission)) {
      abort(403, 'Unauthorized action.');
    }
  }

  protected function authorizeRole(string $role)
  {
    /** @var User|null $user */
    $user = Auth::user();
    if (!$user || !$user->hasRole($role)) {
      abort(403, 'Unauthorized action.');
    }
  }

  protected function authorizeAny(array $permissions)
  {
    /** @var User|null $user */
    $user = Auth::user();
    if (!$user) {
      abort(403, 'Unauthorized action.');
    }

    foreach ($permissions as $permission) {
      if ($user->can($permission)) {
        return;
      }
    }

    abort(403, 'Unauthorized action.');
  }

  protected function authorizeAll(array $permissions)
  {
    /** @var User|null $user */
    $user = Auth::user();
    if (!$user) {
      abort(403, 'Unauthorized action.');
    }

    foreach ($permissions as $permission) {
      if (!$user->can($permission)) {
        abort(403, 'Unauthorized action.');
      }
    }
  }

  protected function authorizeRoles(array $roles)
  {
    /** @var User|null $user */
    $user = Auth::user();
    if (!$user) {
      abort(403, 'Unauthorized action.');
    }

    foreach ($roles as $role) {
      if ($user->hasRole($role)) {
        return;
      }
    }

    abort(403, 'Unauthorized action.');
  }
}
