<?php

namespace App\Contracts;

interface Authorizable
{
    /**
     * Check if the user has a specific permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool;

    /**
     * Get all permissions for the user.
     *
     * @return array
     */
    public function getPermissions(): array;

    /**
     * Check if the user has any of the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions): bool;

    /**
     * Check if the user has all of the given permissions.
     *
     * @param array|string $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions): bool;
}
