<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait HasAuthorization
{
    /**
     * Check if the user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    /**
     * Get all permissions for the user.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        // For now, return all permissions for testing
        // TODO: Implement proper permission retrieval from database
        return [
            'view_leave_requests',
            'create_leave_requests',
            'edit_leave_requests',
            'delete_leave_requests',
            'approve_leave_requests',
            'view_leave_types',
            'create_leave_types',
            'edit_leave_types',
            'delete_leave_types',
            'view_all_leave_requests'
        ];
    }

    /**
     * Check if the user has any of the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions): bool
    {
        $permissions = Arr::wrap($permissions);

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all of the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions): bool
    {
        $permissions = Arr::wrap($permissions);

        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the role names for the user.
     *
     * @return array
     */
    public function getRoles(): array
    {
        // For now, return basic roles for testing
        // TODO: Implement proper role retrieval from database
        return ['user'];
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        $roles = Arr::wrap($roles);

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all of the given roles.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAllRoles($roles): bool
    {
        $roles = Arr::wrap($roles);

        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }
}
