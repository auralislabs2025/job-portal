<?php

namespace App\Policies;

use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_applications');
    }

    public function view(User $user): bool
    {
        return $user->hasPermission('view_applications');
    }

    public function updateStatus(User $user): bool
    {
        return $user->hasPermission('manage_candidates');
    }

    public function assign(User $user): bool
    {
        return $user->hasPermission('manage_candidates');
    }
}
