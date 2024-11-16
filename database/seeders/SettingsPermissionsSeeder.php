<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'View General Settings',
                'slug' => 'view_general_settings',
                'description' => 'Can view general settings'
            ],
            [
                'name' => 'Edit General Settings',
                'slug' => 'edit_general_settings',
                'description' => 'Can edit general settings'
            ],
            [
                'name' => 'View Security Settings',
                'slug' => 'view_security_settings',
                'description' => 'Can view security settings'
            ],
            [
                'name' => 'Edit Security Settings',
                'slug' => 'edit_security_settings',
                'description' => 'Can edit security settings'
            ],
            [
                'name' => 'View Localization Settings',
                'slug' => 'view_localization_settings',
                'description' => 'Can view localization settings'
            ],
            [
                'name' => 'Edit Localization Settings',
                'slug' => 'edit_localization_settings',
                'description' => 'Can edit localization settings'
            ],
            [
                'name' => 'View Email Settings',
                'slug' => 'view_email_settings',
                'description' => 'Can view email settings'
            ],
            [
                'name' => 'Edit Email Settings',
                'slug' => 'edit_email_settings',
                'description' => 'Can edit email settings'
            ],
            [
                'name' => 'View Workflow Settings',
                'slug' => 'view_workflow_settings',
                'description' => 'Can view workflow settings'
            ],
            [
                'name' => 'Edit Workflow Settings',
                'slug' => 'edit_workflow_settings',
                'description' => 'Can edit workflow settings'
            ],
            [
                'name' => 'View API Settings',
                'slug' => 'view_api_settings',
                'description' => 'Can view API settings'
            ],
            [
                'name' => 'Edit API Settings',
                'slug' => 'edit_api_settings',
                'description' => 'Can edit API settings'
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

        // Assign permissions to roles using role_permission table
        if ($adminRole) {
            $adminPermissions = Permission::whereIn('slug', [
                'view_general_settings',
                'edit_general_settings',
                'view_security_settings',
                'edit_security_settings',
                'view_localization_settings',
                'edit_localization_settings',
                'view_email_settings',
                'edit_email_settings',
                'view_workflow_settings',
                'edit_workflow_settings',
                'view_api_settings',
                'edit_api_settings'
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
                'view_general_settings',
                'view_security_settings',
                'view_localization_settings',
                'view_email_settings',
                'view_workflow_settings',
                'view_api_settings'
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
    }
}
