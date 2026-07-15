<?php

namespace Database\Seeders;

use App\Models\GroupCompany;
use App\Models\NotificationGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationGroupSeeder extends Seeder
{
    public function run(): void
    {
        $companies = GroupCompany::pluck('id', 'name');
        $users = User::pluck('id', 'name');

        $groups = [
            ['name' => 'Job Approval Team', 'company' => 'Transport Division', 'members' => ['Ahmed Al Rashid', 'Rajesh Kumar', 'Sarah Wilson']],
            ['name' => 'Recruitment Team', 'company' => 'Transport Division', 'members' => ['Rajesh Kumar', 'Priya Menon']],
            ['name' => 'Management Review', 'company' => 'Petroleum Division', 'members' => ['Mohammed Al Qasimi', 'Rajesh Kumar']],
            ['name' => 'Recruitment Team', 'company' => "Bhavan's Public School", 'members' => ['Rajesh Kumar']],
            ['name' => 'Job Approval Team', 'company' => 'Steel & Engineering', 'members' => ['David Chen', 'Rajesh Kumar']],
            ['name' => 'Executive Approval', 'company' => 'International General Trading', 'members' => ['Rajesh Kumar']],
        ];

        foreach ($groups as $data) {
            $group = NotificationGroup::create([
                'name' => $data['name'],
                'group_company_id' => $companies[$data['company']],
                'description' => $data['name'] . ' — ' . $data['company'],
            ]);

            $memberIds = collect($data['members'])->map(fn ($name) => $users[$name] ?? null)->filter()->values();
            $group->users()->sync($memberIds);
        }
    }
}
