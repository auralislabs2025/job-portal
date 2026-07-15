<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Manage Users', 'slug' => 'manage_users', 'description' => 'Add, edit, deactivate and delete users'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'description' => 'Change what each role is allowed to do'],
            ['name' => 'Manage Companies', 'slug' => 'manage_companies', 'description' => 'Add, edit and delete group companies'],
            ['name' => 'Post Jobs', 'slug' => 'post_jobs', 'description' => 'Create and edit job postings'],
            ['name' => 'Approve Jobs', 'slug' => 'approve_jobs', 'description' => 'Approve or reject pending job postings'],
            ['name' => 'View Applications', 'slug' => 'view_applications', 'description' => 'View candidate applications'],
            ['name' => 'Manage Candidates', 'slug' => 'manage_candidates', 'description' => 'Screen, shortlist and update candidate status'],
            ['name' => 'Manage Notification Groups', 'slug' => 'manage_notification_groups', 'description' => 'Create and edit notification groups'],
            ['name' => 'View Activity Logs', 'slug' => 'view_activity_logs', 'description' => 'View the system-wide activity log'],
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'description' => 'Change system and profile settings'],
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'description' => 'View the dashboard page'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }
    }
}
