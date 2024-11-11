<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
  public function run(): void
  {
    // Create admin role if it doesn't exist
    Role::firstOrCreate(
      ['name' => 'admin'],
      [
        'description' => 'Administrator with full access',
        'permissions' => ['*'],
      ]
    );

    // Create manager role if it doesn't exist
    Role::firstOrCreate(
      ['name' => 'manager'],
      [
        'description' => 'Manager with elevated access',
        'permissions' => [
          'view',
          'create',
          'edit',
          'delete',
          'manage_users',
          'manage_settings',
        ],
      ]
    );

    // Create user role if it doesn't exist
    Role::firstOrCreate(
      ['name' => 'user'],
      [
        'description' => 'Standard user',
        'permissions' => [
          'view',
          'create',
          'edit',
        ],
      ]
    );
  }
}
