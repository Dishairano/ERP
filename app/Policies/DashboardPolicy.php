<?php

namespace App\Policies;

use App\Models\Dashboard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardPolicy
{
  use HandlesAuthorization;

  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return true;
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Dashboard $dashboard): bool
  {
    // User can view if they own the dashboard
    if ($dashboard->user_id === $user->id) {
      return true;
    }

    // User can view if it's a company dashboard
    if ($dashboard->type === 'company') {
      return true;
    }

    // User can view if it's their department's dashboard
    if (
      $dashboard->type === 'department' &&
      $dashboard->department_id === $user->department_id
    ) {
      return true;
    }

    // User can view if it's shared with them
    return $dashboard->sharedUsers()
      ->where('user_id', $user->id)
      ->exists();
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    // Add any specific conditions for creating dashboards
    return true;
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Dashboard $dashboard): bool
  {
    // Owner can always update
    if ($dashboard->user_id === $user->id) {
      return true;
    }

    // Check if user has edit permissions through sharing
    $sharedPermission = $dashboard->sharedUsers()
      ->where('user_id', $user->id)
      ->value('permission_level');

    return in_array($sharedPermission, ['edit', 'manage']);
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Dashboard $dashboard): bool
  {
    // Owner can always delete
    if ($dashboard->user_id === $user->id) {
      return true;
    }

    // Check if user has manage permissions through sharing
    $sharedPermission = $dashboard->sharedUsers()
      ->where('user_id', $user->id)
      ->value('permission_level');

    return $sharedPermission === 'manage';
  }

  /**
   * Determine whether the user can duplicate the model.
   */
  public function duplicate(User $user, Dashboard $dashboard): bool
  {
    // Users can duplicate if they can view the dashboard
    return $this->view($user, $dashboard);
  }

  /**
   * Determine whether the user can share the model.
   */
  public function share(User $user, Dashboard $dashboard): bool
  {
    // Owner can always share
    if ($dashboard->user_id === $user->id) {
      return true;
    }

    // Check if user has manage permissions through sharing
    $sharedPermission = $dashboard->sharedUsers()
      ->where('user_id', $user->id)
      ->value('permission_level');

    return $sharedPermission === 'manage';
  }

  /**
   * Determine whether the user can manage components of the model.
   */
  public function manageComponents(User $user, Dashboard $dashboard): bool
  {
    // Same rules as update
    return $this->update($user, $dashboard);
  }

  /**
   * Determine whether the user can export the model.
   */
  public function export(User $user, Dashboard $dashboard): bool
  {
    // Users can export if they can view the dashboard
    return $this->view($user, $dashboard);
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Dashboard $dashboard): bool
  {
    // Only owners can restore
    return $dashboard->user_id === $user->id;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Dashboard $dashboard): bool
  {
    // Only owners can force delete
    return $dashboard->user_id === $user->id;
  }
}
