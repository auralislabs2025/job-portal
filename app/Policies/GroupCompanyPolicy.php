<?php

namespace App\Policies;

use App\Models\User;

class GroupCompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_companies');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_companies');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('manage_companies');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('manage_companies');
    }
}
