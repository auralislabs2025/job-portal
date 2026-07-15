<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('slug', 'super_admin')->first();
        $hrManager = Role::where('slug', 'hr_manager')->first();
        $recruiter = Role::where('slug', 'recruiter')->first();

        $allSlugs = Permission::pluck('slug')->toArray();

        $hrSlugs = [
            'view_dashboard', 'manage_users', 'manage_roles', 'manage_companies', 'post_jobs', 'approve_jobs',
            'view_applications', 'manage_candidates', 'manage_notification_groups',
            'view_activity_logs', 'manage_settings',
        ];

        $recruiterSlugs = ['view_dashboard', 'post_jobs', 'view_applications', 'manage_candidates'];

        $superAdmin->permissions()->sync(Permission::whereIn('slug', $allSlugs)->pluck('id'));
        $hrManager->permissions()->sync(Permission::whereIn('slug', $hrSlugs)->pluck('id'));
        $recruiter->permissions()->sync(Permission::whereIn('slug', $recruiterSlugs)->pluck('id'));
    }
}
