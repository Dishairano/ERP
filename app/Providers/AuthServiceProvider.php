<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
  protected $policies = [
    // Define your model policies here
  ];

  public function boot()
  {
    $this->registerPolicies();

    // Register permissions
    $permissions = [
      'manage_leave_requests',
      'view_reports',
      'export_reports',
      'manage_schedules',
      'manage_users',
      'manage_roles',
      'manage_settings',
      'view_audit_logs',
      'export_audit_logs'
    ];

    foreach ($permissions as $permission) {
      Gate::define($permission, function (User $user) use ($permission) {
        return $user->hasPermission($permission);
      });
    }

    // Define roles
    Gate::define('isAdmin', function (User $user) {
      return $user->hasRole('admin');
    });

    Gate::define('isManager', function (User $user) {
      return $user->hasRole('manager');
    });

    Gate::define('isEmployee', function (User $user) {
      return $user->hasRole('employee');
    });
  }
}
