<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsPermissionsSeeder extends Seeder
{
  public function run()
  {
    $permissions = [
      // General Settings
      ['name' => 'view_general_settings', 'guard_name' => 'web'],
      ['name' => 'edit_general_settings', 'guard_name' => 'web'],

      // Company Profile
      ['name' => 'view_company_profile', 'guard_name' => 'web'],
      ['name' => 'edit_company_profile', 'guard_name' => 'web'],

      // Notifications
      ['name' => 'view_notification_settings', 'guard_name' => 'web'],
      ['name' => 'edit_notification_settings', 'guard_name' => 'web'],

      // Integrations
      ['name' => 'view_integrations', 'guard_name' => 'web'],
      ['name' => 'edit_integrations', 'guard_name' => 'web'],

      // Backup & Recovery
      ['name' => 'view_backup_settings', 'guard_name' => 'web'],
      ['name' => 'create_backup', 'guard_name' => 'web'],
      ['name' => 'restore_backup', 'guard_name' => 'web'],

      // Roles & Permissions
      ['name' => 'view_roles', 'guard_name' => 'web'],
      ['name' => 'create_roles', 'guard_name' => 'web'],
      ['name' => 'edit_roles', 'guard_name' => 'web'],
      ['name' => 'delete_roles', 'guard_name' => 'web'],

      // User Management
      ['name' => 'view_users', 'guard_name' => 'web'],
      ['name' => 'create_users', 'guard_name' => 'web'],
      ['name' => 'edit_users', 'guard_name' => 'web'],
      ['name' => 'delete_users', 'guard_name' => 'web'],

      // Audit Log
      ['name' => 'view_audit_log', 'guard_name' => 'web'],
      ['name' => 'export_audit_log', 'guard_name' => 'web'],

      // Security Settings
      ['name' => 'view_security_settings', 'guard_name' => 'web'],
      ['name' => 'edit_security_settings', 'guard_name' => 'web'],

      // Localization
      ['name' => 'view_localization_settings', 'guard_name' => 'web'],
      ['name' => 'edit_localization_settings', 'guard_name' => 'web'],

      // Email Configuration
      ['name' => 'view_email_settings', 'guard_name' => 'web'],
      ['name' => 'edit_email_settings', 'guard_name' => 'web'],
      ['name' => 'test_email_settings', 'guard_name' => 'web'],

      // Workflow Settings
      ['name' => 'view_workflow_settings', 'guard_name' => 'web'],
      ['name' => 'edit_workflow_settings', 'guard_name' => 'web'],

      // API Settings
      ['name' => 'view_api_settings', 'guard_name' => 'web'],
      ['name' => 'edit_api_settings', 'guard_name' => 'web'],
      ['name' => 'generate_api_key', 'guard_name' => 'web'],
    ];

    foreach ($permissions as $permission) {
      DB::table('permissions')->insertOrIgnore($permission);
    }

    // Assign all permissions to admin role
    $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
    if ($adminRoleId) {
      $permissionIds = DB::table('permissions')->pluck('id');
      foreach ($permissionIds as $permissionId) {
        DB::table('role_has_permissions')->insertOrIgnore([
          'role_id' => $adminRoleId,
          'permission_id' => $permissionId
        ]);
      }
    }
  }
}
