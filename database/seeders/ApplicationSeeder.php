<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationAdditionalInfo;
use App\Models\ApplicationDocument;
use App\Models\ApplicationDrivingLicense;
use App\Models\ApplicationEducation;
use App\Models\ApplicationEmployment;
use App\Models\ApplicationJobDetail;
use App\Models\ApplicationPassportVisa;
use App\Models\ApplicationPersonalDetail;
use App\Models\ApplicationPreviousEmployer;
use App\Models\ApplicationNote;
use App\Models\ApplicationStatusHistory;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = JobPosting::pluck('id', 'title');
        $users = User::pluck('id', 'name');

        $apps = [
            [
                'job' => 'Senior Fleet Manager', 'name' => 'James Anderson', 'email' => 'james.anderson@email.com',
                'mobile' => '+971 50 123 4567', 'status' => 'screening', 'assigned' => 'Sarah Wilson',
                'submitted' => '2025-06-20T10:30:00',
                'personal' => ['first_name' => 'James', 'last_name' => 'Anderson', 'full_name' => 'James Anderson', 'gender' => 'Male', 'date_of_birth' => '1990-03-15', 'nationality' => 'American', 'marital_status' => 'Married', 'email' => 'james.anderson@email.com', 'mobile' => '+971 50 123 4567', 'current_address' => 'Marina District, Dubai Marina', 'current_country' => 'UAE', 'current_city' => 'Dubai'],
                'job_detail' => ['position_applying_for' => 'Senior Fleet Manager', 'preferred_job_category' => 'Logistics & Supply Chain', 'current_job_title' => 'Fleet Operations Manager', 'current_employer' => 'Global Logistics Inc.', 'employment_status' => 'Notice Period', 'notice_period' => '30 days', 'available_to_join_from' => '2025-08-01'],
                'educations' => [['is_highest' => true, 'qualification_level' => "Bachelor's Degree", 'degree_diploma' => 'B.Sc. Logistics Management', 'specialization' => 'Supply Chain', 'institution' => 'University of Michigan', 'graduation_year' => 2012, 'grade_percentage' => '3.6 GPA']],
                'employment' => ['total_years_experience' => 12.5, 'current_designation' => 'Fleet Operations Manager', 'industry' => 'Logistics', 'key_responsibilities' => 'Managed fleet of 200+ vehicles, optimized routes, reduced fuel costs by 15%', 'relevant_experience' => '12+ years in fleet management', 'last_working_date' => '2025-06-15'],
                'prev_employers' => [
                    ['employer_name' => 'Global Logistics Inc.', 'designation' => 'Fleet Operations Manager', 'industry' => 'Logistics', 'started_on' => '2018-03-01', 'ended_on' => '2025-06-15', 'sort_order' => 0],
                ],
                'passport' => ['passport_number' => '488123456', 'passport_expiry_date' => '2028-06-15', 'passport_issuing_country' => 'United States', 'visa_status' => 'Employment Visa'],
                'driving' => ['license_number' => 'UAE-DL-1234567', 'license_country' => 'UAE', 'license_type' => 'Light Vehicle', 'license_expiry_date' => '2027-05-20'],
                'docs' => [['document_type' => 'resume', 'original_filename' => 'James_Anderson_Resume.pdf', 'mime_type' => 'application/pdf', 'file_size_bytes' => 245000]],
                'additional' => ['willing_to_relocate' => true, 'willing_to_travel' => true, 'interview_availability' => 'Weekdays after 2 PM'],
                'history' => [
                    ['previous' => null, 'current' => 'pending', 'user' => null, 'when' => '2025-06-20T10:30:00'],
                    ['previous' => 'pending', 'current' => 'screening', 'user' => 'Rajesh Kumar', 'when' => '2025-06-22T09:15:00'],
                ],
                'notes' => [['note' => 'Strong candidate with relevant fleet management experience.', 'user' => 'Rajesh Kumar', 'visibility' => 'shared']],
            ],
            [
                'job' => 'Petroleum Engineer', 'name' => 'Maria Garcia', 'email' => 'maria.garcia@email.com',
                'mobile' => '+971 55 234 5678', 'status' => 'interview', 'assigned' => 'Rajesh Kumar',
                'submitted' => '2025-06-15T14:20:00',
                'personal' => ['first_name' => 'Maria', 'last_name' => 'Garcia', 'full_name' => 'Maria Garcia', 'gender' => 'Female', 'date_of_birth' => '1988-11-02', 'nationality' => 'Spanish', 'marital_status' => 'Single', 'email' => 'maria.garcia@email.com', 'mobile' => '+971 55 234 5678', 'current_address' => 'Al Reem Island, Abu Dhabi', 'current_country' => 'UAE', 'current_city' => 'Abu Dhabi'],
                'job_detail' => ['position_applying_for' => 'Petroleum Engineer', 'preferred_job_category' => 'Oil & Gas', 'current_job_title' => 'Senior Petroleum Engineer', 'current_employer' => 'Oman Oil Co.', 'employment_status' => 'Employed', 'notice_period' => '60 days'],
                'educations' => [['is_highest' => true, 'qualification_level' => "Master's Degree", 'degree_diploma' => 'M.Sc. Petroleum Engineering', 'specialization' => 'Reservoir Engineering', 'institution' => 'Imperial College London', 'graduation_year' => 2011, 'grade_percentage' => 'Distinction']],
                'employment' => ['total_years_experience' => 14, 'current_designation' => 'Senior Petroleum Engineer', 'industry' => 'Oil & Gas'],
                'prev_employers' => [
                    ['employer_name' => 'Oman Oil Co.', 'designation' => 'Petroleum Engineer', 'industry' => 'Oil & Gas', 'started_on' => '2020-01-01', 'sort_order' => 0],
                ],
                'passport' => ['passport_number' => 'XZ789456', 'passport_expiry_date' => '2027-08-20', 'passport_issuing_country' => 'Spain', 'visa_status' => 'Employment Visa'],
                'driving' => ['license_number' => 'UAE-DL-7654321', 'license_country' => 'UAE', 'license_type' => 'Light Vehicle', 'license_expiry_date' => '2026-02-10'],
                'docs' => [['document_type' => 'resume', 'original_filename' => 'Maria_Garcia_Resume.pdf', 'mime_type' => 'application/pdf', 'file_size_bytes' => 312000]],
                'additional' => ['willing_to_relocate' => false, 'willing_to_travel' => true],
                'history' => [
                    ['previous' => null, 'current' => 'pending', 'user' => null, 'when' => '2025-06-15T14:20:00'],
                    ['previous' => 'pending', 'current' => 'screening', 'user' => 'Mohammed Al Qasimi', 'when' => '2025-06-17T10:00:00'],
                    ['previous' => 'screening', 'current' => 'interview', 'user' => 'Rajesh Kumar', 'when' => '2025-06-20T11:30:00'],
                ],
                'notes' => [],
            ],
            [
                'job' => 'Structural Engineer', 'name' => 'Ahmed Hassan', 'email' => 'ahmed.hassan@email.com',
                'mobile' => '+971 52 345 6789', 'status' => 'offer', 'assigned' => 'David Chen',
                'submitted' => '2025-06-01T08:45:00',
                'personal' => ['first_name' => 'Ahmed', 'last_name' => 'Hassan', 'full_name' => 'Ahmed Hassan', 'gender' => 'Male', 'date_of_birth' => '1992-07-18', 'nationality' => 'Egyptian', 'marital_status' => 'Married', 'email' => 'ahmed.hassan@email.com', 'mobile' => '+971 52 345 6789', 'current_address' => 'Al Nahda, Dubai', 'current_country' => 'UAE', 'current_city' => 'Dubai'],
                'job_detail' => ['position_applying_for' => 'Structural Engineer', 'preferred_job_category' => 'Engineering & Construction', 'current_job_title' => 'Structural Design Engineer', 'current_employer' => 'Al Futtaim Engineering', 'employment_status' => 'Notice Period', 'notice_period' => '30 days', 'available_to_join_from' => '2025-07-15'],
                'educations' => [['is_highest' => true, 'qualification_level' => "Bachelor's Degree", 'degree_diploma' => 'B.Sc. Civil Engineering', 'specialization' => 'Structural', 'institution' => 'Cairo University', 'graduation_year' => 2014, 'grade_percentage' => '86%']],
                'employment' => ['total_years_experience' => 11, 'current_designation' => 'Structural Design Engineer', 'industry' => 'Construction'],
                'prev_employers' => [
                    ['employer_name' => 'Al Futtaim Engineering', 'designation' => 'Structural Design Engineer', 'industry' => 'Construction', 'started_on' => '2019-04-01', 'ended_on' => '2025-06-30', 'sort_order' => 0],
                ],
                'passport' => ['passport_number' => 'EG987654', 'passport_expiry_date' => '2029-01-10', 'passport_issuing_country' => 'Egypt', 'visa_status' => 'Employment Visa'],
                'driving' => ['license_number' => 'UAE-DL-5566778', 'license_country' => 'UAE', 'license_type' => 'Heavy Vehicle', 'license_expiry_date' => '2028-11-15'],
                'docs' => [['document_type' => 'resume', 'original_filename' => 'Ahmed_Hassan_Resume.pdf', 'mime_type' => 'application/pdf', 'file_size_bytes' => 198000]],
                'additional' => ['willing_to_relocate' => true, 'willing_to_travel' => true],
                'history' => [
                    ['previous' => null, 'current' => 'pending', 'user' => null, 'when' => '2025-06-01T08:45:00'],
                    ['previous' => 'pending', 'current' => 'screening', 'user' => 'David Chen', 'when' => '2025-06-03T10:00:00'],
                    ['previous' => 'screening', 'current' => 'interview', 'user' => 'David Chen', 'when' => '2025-06-05T14:00:00'],
                    ['previous' => 'interview', 'current' => 'offer', 'user' => 'David Chen', 'when' => '2025-06-12T09:30:00'],
                ],
                'notes' => [['note' => 'Excellent technical skills. Offered position with compensation package.', 'user' => 'David Chen', 'visibility' => 'shared']],
            ],
        ];

        foreach ($apps as $data) {
            $app = Application::create([
                'job_posting_id' => $jobs[$data['job']],
                'candidate_display_name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'assigned_to' => $data['assigned'] ? ($users[$data['assigned']] ?? null) : null,
                'status' => $data['status'],
                'submitted_at' => $data['submitted'],
                'declared_information_accurate' => true,
                'declared_authorize_verification' => true,
                'declared_data_consent' => true,
                'declared_at' => $data['submitted'],
            ]);

            if ($data['personal']) $app->personalDetails()->create($data['personal']);
            if ($data['job_detail']) $app->jobDetails()->create($data['job_detail']);

            foreach ($data['educations'] as $edu) {
                $app->educations()->create($edu);
            }

            if ($data['employment']) $app->employment()->create($data['employment']);

            foreach ($data['prev_employers'] as $emp) {
                $app->previousEmployers()->create($emp);
            }

            if ($data['passport']) $app->passportVisa()->create($data['passport']);
            if ($data['driving']) $app->drivingLicense()->create($data['driving']);

            foreach ($data['docs'] as $doc) {
                $filename = pathinfo($doc['original_filename'], PATHINFO_FILENAME);
                $dir = 'resumes';
                $path = $dir . '/' . $filename . '.pdf';
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->put($path, 'Placeholder PDF content for ' . $doc['original_filename']);
                }
                $app->documents()->create(array_merge($doc, ['file_path' => $path]));
            }

            if ($data['additional']) $app->additionalInfo()->create($data['additional']);

            foreach ($data['history'] as $h) {
                $app->statusHistories()->create([
                    'previous_status' => $h['previous'],
                    'current_status' => $h['current'],
                    'changed_by' => $h['user'] ? ($users[$h['user']] ?? null) : null,
                    'changed_at' => $h['when'],
                ]);
            }

            foreach ($data['notes'] as $n) {
                $app->notes()->create([
                    'note' => $n['note'],
                    'user_id' => $users[$n['user']] ?? $users['Rajesh Kumar'],
                    'visibility' => $n['visibility'],
                    'created_at' => $data['submitted'],
                ]);
            }
        }
    }
}
