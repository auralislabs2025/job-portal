<?php

namespace App\Policies;

use App\Models\ApplicationNote;
use App\Models\User;

class ApplicationNotePolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermission('manage_candidates');
    }

    public function delete(User $user, ApplicationNote $note): bool
    {
        return $user->hasPermission('manage_candidates') && ($user->id === $note->user_id || $user->hasPermission('manage_users'));
    }
}
