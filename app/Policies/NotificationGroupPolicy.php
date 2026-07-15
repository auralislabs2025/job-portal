<?php

namespace App\Policies;

use App\Models\User;

class NotificationGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage_notification_groups');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_notification_groups');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('manage_notification_groups');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('manage_notification_groups');
    }
}
