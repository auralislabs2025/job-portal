<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            GroupCompanySeeder::class,
            UserSeeder::class,
            NotificationGroupSeeder::class,
            JobPostingSeeder::class,
            ApplicationSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}
