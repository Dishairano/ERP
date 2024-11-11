<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      RoleSeeder::class, // Must run first to set up roles
      SettingsPermissionsSeeder::class, // Must run after RoleSeeder to assign permissions
      DepartmentSeeder::class,
      ProjectTemplateSeeder::class, // Must run before ProjectSeeder
      ProjectSeeder::class,
      CostCategorySeeder::class,
    ]);
  }
}
