<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'Full access to every module.',
            'is_builtin' => true,
        ]);

        Role::create([
            'name' => 'HR Manager',
            'slug' => 'hr_manager',
            'description' => 'Manages recruitment operations across all divisions.',
            'is_builtin' => false,
        ]);

        Role::create([
            'name' => 'Recruiter',
            'slug' => 'recruiter',
            'description' => 'Posts jobs and manages candidates for their division.',
            'is_builtin' => false,
        ]);
    }
}
