<?php

namespace App\Policies;

use App\Models\User;

class JobPostingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('post_jobs');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('post_jobs');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('post_jobs');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('post_jobs');
    }

    public function updateStatus(User $user): bool
    {
        return $user->hasPermission('approve_jobs');
    }
}
