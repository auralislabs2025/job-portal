<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id', 'name');
        $rajesh = $users['Rajesh Kumar'];

        $logs = [
            ['action' => 'Job Posted', 'module' => 'job_postings', 'record_id' => 1, 'user_id' => $rajesh, 'created_at' => '2025-07-05 14:30:00'],
            ['action' => 'Application Received', 'module' => 'applications', 'record_id' => 1, 'user_id' => null, 'created_at' => '2025-07-05 13:15:00'],
            ['action' => 'Job Approved', 'module' => 'job_postings', 'record_id' => 3, 'user_id' => $users['Mohammed Al Qasimi'], 'created_at' => '2025-07-05 11:00:00'],
            ['action' => 'Candidate Shortlisted', 'module' => 'applications', 'record_id' => 2, 'user_id' => $rajesh, 'created_at' => '2025-07-05 09:45:00'],
            ['action' => 'Interview Scheduled', 'module' => 'applications', 'record_id' => 2, 'user_id' => $users['Sarah Wilson'], 'created_at' => '2025-07-04 16:20:00'],
        ];

        foreach ($logs as $log) {
            ActivityLog::create($log);
        }
    }
}
