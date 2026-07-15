<?php

namespace App\Services;

use App\Models\Application;
use App\Models\JobPosting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationSubmissionService
{
    public function submit(JobPosting $job, array $data): Application
    {
        return DB::transaction(function () use ($job, $data) {
            $application = Application::create([
                'job_posting_id'                => $job->id,
                'candidate_display_name'        => trim(($data['personal']['first_name'] ?? '') . ' ' . ($data['personal']['last_name'] ?? '')),
                'email'                         => $data['personal']['email'] ?? null,
                'mobile'                        => $data['personal']['mobile'] ?? null,
                'status'                        => 'pending',
                'submitted_at'                  => now(),
                'declared_information_accurate'  => true,
                'declared_authorize_verification' => true,
                'declared_data_consent'          => true,
                'declared_at'                   => now(),
            ]);

            $application->code = 'APP' . str_pad((string) $application->id, 5, '0', STR_PAD_LEFT);
            $application->save();

            $this->createPersonalDetails($application, $data['personal'] ?? []);
            $this->createJobDetails($application, $data['job'] ?? []);
            $this->createEducations($application, $data['education'] ?? []);
            $this->createEmployment($application, $data['employment'] ?? []);
            $this->createPreviousEmployers($application, $data['previous_employers'] ?? []);
            $this->createPassportVisa($application, $data['passport'] ?? []);
            $this->createDrivingLicense($application, $data['driving'] ?? []);
            $this->storeDocuments($application, $data['files'] ?? []);
            $this->createAdditionalInfo($application, $data['additional'] ?? []);

            ActivityLogger::log(
                action: 'submitted',
                module: 'applications',
                recordId: $application->id,
                userId: null,
            );

            return $application;
        });
    }

    private function createPersonalDetails(Application $application, array $data): void
    {
        $application->personalDetails()->create([
            'photo_path'         => isset($data['photo_path']) ? $data['photo_path'] : null,
            'first_name'         => $data['first_name'] ?? null,
            'last_name'          => $data['last_name'] ?? null,
            'full_name'          => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
            'gender'             => $data['gender'] ?? null,
            'date_of_birth'      => $data['date_of_birth'] ?? null,
            'nationality'        => $data['nationality'] ?? null,
            'marital_status'     => $data['marital_status'] ?? null,
            'email'              => $data['email'] ?? null,
            'mobile'             => $data['mobile'] ?? null,
            'alternate_contact'  => $data['alternate_contact'] ?? null,
            'current_address'    => $data['current_address'] ?? null,
            'permanent_address'  => $data['permanent_address'] ?? null,
            'current_country'    => $data['current_country'] ?? null,
            'current_city'       => $data['current_city'] ?? null,
            'postal_code'        => $data['postal_code'] ?? null,
        ]);
    }

    private function createJobDetails(Application $application, array $data): void
    {
        $application->jobDetails()->create([
            'position_applying_for'  => $data['position_applying_for'] ?? null,
            'preferred_job_category' => $data['preferred_job_category'] ?? null,
            'current_job_title'      => $data['current_job_title'] ?? null,
            'current_employer'       => $data['current_employer'] ?? null,
            'employment_status'      => $data['employment_status'] ?? null,
            'notice_period'          => $data['notice_period'] ?? null,
            'available_to_join_from' => $data['available_to_join_from'] ?? null,
        ]);
    }

    private function createEducations(Application $application, array $data): void
    {
        $rows = isset($data['qualification_level']) && is_array($data['qualification_level'])
            ? $this->transposeEducationRows($data)
            : [$data];

        foreach ($rows as $row) {
            $application->educations()->create([
                'is_highest'          => $row['is_highest'] ?? null,
                'qualification_level' => $row['qualification_level'] ?? null,
                'degree_diploma'      => $row['degree_diploma'] ?? null,
                'specialization'      => $row['specialization'] ?? null,
                'institution'         => $row['institution'] ?? null,
                'graduation_year'     => $row['graduation_year'] ?? null,
                'grade_percentage'    => $row['grade_percentage'] ?? null,
            ]);
        }
    }

    private function transposeEducationRows(array $data): array
    {
        $rows = [];
        $keys = ['is_highest', 'qualification_level', 'degree_diploma', 'specialization', 'institution', 'graduation_year', 'grade_percentage'];
        $count = count($data['qualification_level']);
        for ($i = 0; $i < $count; $i++) {
            $row = [];
            foreach ($keys as $key) {
                $row[$key] = $data[$key][$i] ?? null;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    private function createEmployment(Application $application, array $data): void
    {
        $application->employment()->create([
            'total_years_experience' => $data['total_years_experience'] ?? null,
            'current_designation'    => $data['current_designation'] ?? null,
            'industry'               => $data['industry'] ?? null,
            'key_responsibilities'   => $data['key_responsibilities'] ?? null,
            'relevant_experience'    => $data['relevant_experience'] ?? null,
            'last_working_date'      => $data['last_working_date'] ?? null,
        ]);
    }

    private function createPreviousEmployers(Application $application, array $data): void
    {
        if (empty($data) || empty($data[0] ?? null)) {
            return;
        }

        foreach ($data as $i => $row) {
            if (empty($row['employer_name'])) {
                continue;
            }
            $application->previousEmployers()->create([
                'employer_name'   => $row['employer_name'] ?? null,
                'designation'     => $row['designation'] ?? null,
                'industry'        => $row['industry'] ?? null,
                'responsibilities' => $row['responsibilities'] ?? null,
                'started_on'      => $row['started_on'] ?? null,
                'ended_on'        => $row['ended_on'] ?? null,
                'sort_order'      => $i,
            ]);
        }
    }

    private function createPassportVisa(Application $application, array $data): void
    {
        if (empty(array_filter($data))) {
            return;
        }

        $application->passportVisa()->create([
            'passport_number'        => $data['passport_number'] ?? null,
            'passport_expiry_date'   => $data['passport_expiry_date'] ?? null,
            'passport_issuing_country' => $data['passport_issuing_country'] ?? null,
            'visa_status'            => $data['visa_status'] ?? null,
            'visa_type'              => $data['visa_type'] ?? null,
            'visa_expiry_date'       => $data['visa_expiry_date'] ?? null,
        ]);
    }

    private function createDrivingLicense(Application $application, array $data): void
    {
        if (empty(array_filter($data))) {
            return;
        }

        $application->drivingLicense()->create([
            'license_number'      => $data['license_number'] ?? null,
            'license_country'     => $data['license_country'] ?? null,
            'license_type'        => $data['license_type'] ?? null,
            'license_expiry_date' => $data['license_expiry_date'] ?? null,
        ]);
    }

    private function storeDocuments(Application $application, array $files): void
    {
        $documentTypes = ['photo', 'resume', 'passport_copy', 'visa_copy', 'education_certificate',
            'experience_certificate', 'driving_license', 'professional_certification', 'other'];

        foreach ($documentTypes as $type) {
            if (!isset($files[$type]) || !($files[$type] instanceof UploadedFile)) {
                continue;
            }

            $file = $files[$type];
            $path = $file->store("applications/{$application->id}/{$type}", 'local');

            $application->documents()->create([
                'document_type'    => $type,
                'file_path'        => $path,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type'        => $file->getMimeType(),
                'file_size_bytes'  => $file->getSize(),
                'uploaded_at'      => now(),
            ]);

            if ($type === 'photo') {
                $application->personalDetails()->update(['photo_path' => $path]);
            }
        }
    }

    private function createAdditionalInfo(Application $application, array $data): void
    {
        if (empty(array_filter($data, fn ($v) => $v !== null && $v !== ''))) {
            return;
        }

        $application->additionalInfo()->create([
            'cover_letter'          => $data['cover_letter'] ?? null,
            'willing_to_relocate'   => isset($data['willing_to_relocate']),
            'willing_to_travel'     => isset($data['willing_to_travel']),
            'interview_availability' => $data['interview_availability'] ?? null,
            'linkedin_url'          => $data['linkedin_url'] ?? null,
            'portfolio_url'         => $data['portfolio_url'] ?? null,
            'reference_name'        => $data['reference_name'] ?? null,
            'reference_contact'     => $data['reference_contact'] ?? null,
            'additional_comments'   => $data['additional_comments'] ?? null,
        ]);
    }
}
