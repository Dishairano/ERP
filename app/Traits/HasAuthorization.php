<?php

namespace App\Traits;

trait HasAuthorization
{
  protected $cachedPermissions = null;

  public function hasRole(string $role): bool
  {
    // For development purposes, consider user ID 1 as admin
    if ($this->id === 1) {
      return true;
    }

    // Add role check logic here
    // For now, return true for basic roles
    return in_array($role, ['employee']);
  }

  public function hasPermission(string $permission): bool
  {
    // Cache permissions to avoid multiple checks
    if ($this->cachedPermissions === null) {
      $this->cachedPermissions = $this->getPermissions();
    }

    // For development purposes, consider user ID 1 as having all permissions
    if ($this->id === 1) {
      return true;
    }

    return in_array($permission, $this->cachedPermissions);
  }

  protected function getPermissions(): array
  {
    // This method should be implemented to fetch permissions from the database
    // For now, return a basic set of permissions
    return [
      'view_dashboard',
      'manage_users',
      'view_users',
      'manage_roles',
      'view_roles',
      'manage_settings',
      'view_settings',
      'manage_projects',
      'view_projects',
      'manage_tasks',
      'view_tasks',
      'manage_risks',
      'view_risks',
      'view_audit_logs',
      'export_audit_logs',
      'manage_leave_requests',
      'view_reports',
      'export_reports',
      'manage_schedules',
      'manage_time_registrations'
    ];
  }
}
