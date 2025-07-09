<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Determine whether the user can manage roles.
     */
    public function manageRoles(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage permissions.
     */
    public function managePermissions(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage users.
     */
    public function manageUsers(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
