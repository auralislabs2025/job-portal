<?php

namespace Database\Seeders;

use App\Models\GroupCompany;
use App\Models\JobPosting;
use App\Models\NotificationGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $companies = GroupCompany::pluck('id', 'name');
        $users = User::pluck('id', 'name');
        $ng = NotificationGroup::pluck('id', 'name');

        $rajesh = $users['Rajesh Kumar'];
        $david = $users['David Chen'];
        $mohammed = $users['Mohammed Al Qasimi'];

        $jobs = [
            ['title' => 'Senior Fleet Manager', 'company' => 'Transport Division', 'created' => $rajesh, 'type' => 'full_time', 'location' => 'Dubai, UAE', 'salary' => 'AED 25,000 - 35,000', 'deadline' => '2025-07-15', 'status' => 'published', 'ng' => ['Job Approval Team', 'Recruitment Team']],
            ['title' => 'Logistics Coordinator', 'company' => 'Transport Division', 'created' => $users['Sarah Wilson'], 'type' => 'full_time', 'location' => 'Abu Dhabi, UAE', 'salary' => 'AED 12,000 - 18,000', 'deadline' => '2025-07-28', 'status' => 'pending', 'ng' => ['Job Approval Team']],
            ['title' => 'Petroleum Engineer', 'company' => 'Petroleum Division', 'created' => $mohammed, 'type' => 'full_time', 'location' => 'Kuwait City', 'salary' => 'AED 30,000 - 45,000', 'deadline' => '2025-08-01', 'status' => 'published', 'ng' => ['Management Review']],
            ['title' => 'Heavy Equipment Operator', 'company' => 'Equipment Division', 'created' => $users['Suresh Patel'], 'type' => 'full_time', 'location' => 'Sharjah, UAE', 'salary' => 'AED 8,000 - 12,000', 'deadline' => '2025-07-30', 'status' => 'draft', 'ng' => []],
            ['title' => 'Structural Engineer', 'company' => 'Steel & Engineering', 'created' => $david, 'type' => 'full_time', 'location' => 'Dubai, UAE', 'salary' => 'AED 20,000 - 28,000', 'deadline' => '2025-07-20', 'status' => 'published', 'ng' => ['Job Approval Team']],
            ['title' => 'Math Teacher - Secondary', 'company' => "Bhavan's Public School", 'created' => $rajesh, 'type' => 'full_time', 'location' => 'Dubai, UAE', 'salary' => 'AED 10,000 - 15,000', 'deadline' => '2025-08-15', 'status' => 'pending', 'ng' => ['Recruitment Team']],
            ['title' => 'Trading Manager', 'company' => 'International General Trading', 'created' => $rajesh, 'type' => 'full_time', 'location' => 'Dubai, UAE', 'salary' => 'AED 22,000 - 30,000', 'deadline' => '2025-07-25', 'status' => 'published', 'ng' => ['Executive Approval']],
            ['title' => 'Fleet Maintenance Supervisor', 'company' => 'International Transport', 'created' => $rajesh, 'type' => 'full_time', 'location' => 'Jebel Ali, UAE', 'salary' => 'AED 15,000 - 20,000', 'deadline' => '2025-07-18', 'status' => 'draft', 'ng' => []],
        ];

        foreach ($jobs as $data) {
            $job = JobPosting::create([
                'title' => $data['title'],
                'group_company_id' => $companies[$data['company']],
                'created_by' => $data['created'],
                'employment_type' => $data['type'],
                'location' => $data['location'],
                'salary' => $data['salary'],
                'deadline' => $data['deadline'],
                'description' => "Position: {$data['title']}\nLocation: {$data['location']}\nSalary: {$data['salary']}\n\nWe are looking for an experienced professional to join our team at {$data['company']}.",
                'status' => $data['status'],
            ]);

            if ($data['ng']) {
                $ngIds = collect($data['ng'])->map(fn ($name) => $ng[$name] ?? null)->filter()->values();
                $job->notificationGroups()->sync($ngIds);
            }
        }
    }
}
