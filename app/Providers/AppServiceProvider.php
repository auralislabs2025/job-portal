<?php

namespace App\Providers;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\GroupCompany;
use App\Models\JobPosting;
use App\Models\NotificationGroup;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach (Permission::pluck('slug') as $slug) {
            Gate::define($slug, fn (User $user) => $user->hasPermission($slug));
        }

        Gate::define('manage_companies', fn (User $user) => $user->hasPermission('manage_companies'));
        Gate::define('post_jobs', fn (User $user) => $user->hasPermission('post_jobs'));
        Gate::define('approve_jobs', fn (User $user) => $user->hasPermission('approve_jobs'));
        Gate::define('view_applications', fn (User $user) => $user->hasPermission('view_applications'));
        Gate::define('manage_candidates', fn (User $user) => $user->hasPermission('manage_candidates'));
        Gate::define('manage_notification_groups', fn (User $user) => $user->hasPermission('manage_notification_groups'));
        Gate::define('view_activity_logs', fn (User $user) => $user->hasPermission('view_activity_logs'));
        Gate::define('manage_settings', fn (User $user) => $user->hasPermission('manage_settings'));
        Gate::define('manage_users', fn (User $user) => $user->hasPermission('manage_users'));
        Gate::define('manage_roles', fn (User $user) => $user->hasPermission('manage_roles'));
        Gate::define('view_dashboard', fn (User $user) => $user->hasPermission('view_dashboard'));
    }
}
