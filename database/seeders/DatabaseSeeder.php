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
            RoleSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            HrmSeeder::class,
            LeaveTypeSeeder::class,
            LeaveRequestPermissionsSeeder::class,
            SettingsPermissionsSeeder::class,
            ProjectTemplateSeeder::class,
            ProjectSeeder::class,
            ProjectsAndTasksSeeder::class,
        ]);
    }
}
