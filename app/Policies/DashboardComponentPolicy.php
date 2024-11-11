<?php

namespace App\Policies;

use App\Models\DashboardComponent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DashboardComponentPolicy
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
  public function view(User $user, DashboardComponent $component): bool
  {
    // Delegate to dashboard policy
    return $user->can('view', $component->dashboard);
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return true;
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, DashboardComponent $component): bool
  {
    // Delegate to dashboard policy
    return $user->can('manageComponents', $component->dashboard);
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, DashboardComponent $component): bool
  {
    // Delegate to dashboard policy
    return $user->can('manageComponents', $component->dashboard);
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, DashboardComponent $component): bool
  {
    // Only dashboard owner can restore components
    return $component->dashboard->user_id === $user->id;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, DashboardComponent $component): bool
  {
    // Only dashboard owner can force delete components
    return $component->dashboard->user_id === $user->id;
  }

  /**
   * Determine whether the user can refresh the component data.
   */
  public function refreshData(User $user, DashboardComponent $component): bool
  {
    // Users can refresh data if they can view the component
    return $this->view($user, $component);
  }

  /**
   * Determine whether the user can configure the component.
   */
  public function configure(User $user, DashboardComponent $component): bool
  {
    // Delegate to dashboard policy
    return $user->can('manageComponents', $component->dashboard);
  }

  /**
   * Determine whether the user can export the component data.
   */
  public function export(User $user, DashboardComponent $component): bool
  {
    // Users can export if they can view the component
    return $this->view($user, $component);
  }
}
