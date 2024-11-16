<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System administrator with full access'
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Department manager with approval rights'
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Regular employee with basic access'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
