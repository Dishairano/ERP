<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveRequestPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'View Leave Requests',
                'slug' => 'view_leave_requests',
                'description' => 'Can view leave requests'
            ],
            [
                'name' => 'Create Leave Requests',
                'slug' => 'create_leave_requests',
                'description' => 'Can create new leave requests'
            ],
            [
                'name' => 'Edit Leave Requests',
                'slug' => 'edit_leave_requests',
                'description' => 'Can edit draft leave requests'
            ],
            [
                'name' => 'Delete Leave Requests',
                'slug' => 'delete_leave_requests',
                'description' => 'Can delete leave requests'
            ],
            [
                'name' => 'Approve Leave Requests',
                'slug' => 'approve_leave_requests',
                'description' => 'Can approve or reject leave requests'
            ],
            [
                'name' => 'View All Leave Requests',
                'slug' => 'view_all_leave_requests',
                'description' => 'Can view all users leave requests'
            ],
            [
                'name' => 'View Leave Types',
                'slug' => 'view_leave_types',
                'description' => 'Can view leave types'
            ],
            [
                'name' => 'Create Leave Types',
                'slug' => 'create_leave_types',
                'description' => 'Can create new leave types'
            ],
            [
                'name' => 'Edit Leave Types',
                'slug' => 'edit_leave_types',
                'description' => 'Can edit leave types'
            ],
            [
                'name' => 'Delete Leave Types',
                'slug' => 'delete_leave_types',
                'description' => 'Can delete leave types'
            ]
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $employeeRole = Role::where('slug', 'employee')->first();

        // Assign permissions to roles using role_permission table
        if ($adminRole) {
            $adminPermissions = Permission::whereIn('slug', [
                'view_leave_requests',
                'create_leave_requests',
                'edit_leave_requests',
                'delete_leave_requests',
                'approve_leave_requests',
                'view_all_leave_requests',
                'view_leave_types',
                'create_leave_types',
                'edit_leave_types',
                'delete_leave_types'
            ])->get();

            foreach ($adminPermissions as $permission) {
                DB::table('role_permission')->updateOrInsert(
                    [
                        'role_id' => $adminRole->id,
                        'permission_id' => $permission->id
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }

        if ($managerRole) {
            $managerPermissions = Permission::whereIn('slug', [
                'view_leave_requests',
                'create_leave_requests',
                'edit_leave_requests',
                'approve_leave_requests',
                'view_all_leave_requests',
                'view_leave_types'
            ])->get();

            foreach ($managerPermissions as $permission) {
                DB::table('role_permission')->updateOrInsert(
                    [
                        'role_id' => $managerRole->id,
                        'permission_id' => $permission->id
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }

        if ($employeeRole) {
            $employeePermissions = Permission::whereIn('slug', [
                'view_leave_requests',
                'create_leave_requests',
                'edit_leave_requests',
                'view_leave_types'
            ])->get();

            foreach ($employeePermissions as $permission) {
                DB::table('role_permission')->updateOrInsert(
                    [
                        'role_id' => $employeeRole->id,
                        'permission_id' => $permission->id
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
