<?php

namespace Database\Seeders;

use App\Models\GroupCompany;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::where('slug', 'super_admin')->first();
        $hrManager = Role::where('slug', 'hr_manager')->first();
        $recruiter = Role::where('slug', 'recruiter')->first();

        $allCompanies = GroupCompany::pluck('id', 'name');

        $users = [
            ['name' => 'Rajesh Kumar', 'email' => 'rajesh.kumar@abncorporation.com', 'role' => $hrManager, 'company' => null, 'status' => 'active'],
            ['name' => 'Sarah Wilson', 'email' => 'sarah.wilson@abncorporation.com', 'role' => $recruiter, 'company' => 'Transport Division', 'status' => 'active'],
            ['name' => 'Ahmed Al Rashid', 'email' => 'ahmed.rashid@abncorporation.com', 'role' => $hrManager, 'company' => 'Transport Division', 'status' => 'active'],
            ['name' => 'Mohammed Al Qasimi', 'email' => 'mohammed.qasimi@abncorporation.com', 'role' => $hrManager, 'company' => 'Petroleum Division', 'status' => 'active'],
            ['name' => 'Suresh Patel', 'email' => 'suresh.patel@abncorporation.com', 'role' => $recruiter, 'company' => 'Equipment Division', 'status' => 'active'],
            ['name' => 'Priya Menon', 'email' => 'priya.menon@abncorporation.com', 'role' => $recruiter, 'company' => 'Transport Division', 'status' => 'inactive'],
            ['name' => 'David Chen', 'email' => 'david.chen@abncorporation.com', 'role' => $hrManager, 'company' => 'Steel & Engineering', 'status' => 'active'],
        ];

        foreach ($users as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role_id' => $data['role']->id,
                'group_company_id' => $data['company'] ? $allCompanies[$data['company']] : null,
                'status' => $data['status'],
                'email_verified_at' => now(),
            ]);

            UserSetting::create([
                'user_id' => $user->id,
                'notify_new_applications' => true,
                'notify_job_approvals' => true,
                'notify_weekly_reports' => false,
                'dark_mode' => false,
            ]);
        }
    }
}
