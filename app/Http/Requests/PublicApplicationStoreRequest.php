<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicApplicationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'personal.first_name'            => 'required|string|max:100',
            'personal.last_name'             => 'required|string|max:100',
            'personal.gender'                => 'nullable|string|max:50',
            'personal.date_of_birth'         => 'nullable|date',
            'personal.nationality'           => 'required|string|max:100',
            'personal.marital_status'        => 'nullable|string|max:50',
            'personal.email'                 => 'required|email|max:255',
            'personal.mobile'                => 'required|string|max:50',
            'personal.alternate_contact'     => 'nullable|string|max:50',
            'personal.current_address'       => 'nullable|string|max:1000',
            'personal.permanent_address'     => 'nullable|string|max:1000',
            'personal.current_country'       => 'nullable|string|max:100',
            'personal.current_city'          => 'nullable|string|max:100',
            'personal.postal_code'           => 'nullable|string|max:20',

            'job.position_applying_for'      => 'required|string|max:255',
            'job.preferred_job_category'     => 'required|string|max:100',
            'job.current_job_title'          => 'nullable|string|max:255',
            'job.current_employer'           => 'nullable|string|max:255',
            'job.employment_status'          => 'nullable|string|max:50',
            'job.notice_period'              => 'nullable|string|max:50',
            'job.available_to_join_from'     => 'nullable|date',

            'education.qualification_level'  => 'required|string|max:100',
            'education.degree_diploma'       => 'nullable|string|max:255',
            'education.specialization'       => 'nullable|string|max:255',
            'education.institution'          => 'nullable|string|max:255',
            'education.graduation_year'      => 'nullable|integer|min:1950|max:2100',
            'education.grade_percentage'     => 'nullable|string|max:50',

            'employment.total_years_experience' => 'required|numeric|min:0|max:99',
            'employment.current_designation'    => 'nullable|string|max:255',
            'employment.industry'               => 'nullable|string|max:100',
            'employment.key_responsibilities'   => 'nullable|string|max:5000',
            'employment.relevant_experience'    => 'nullable|string|max:5000',
            'employment.last_working_date'      => 'nullable|date',

            'previous_employers.*.employer_name' => 'nullable|string|max:255',
            'previous_employers.*.designation'   => 'nullable|string|max:255',
            'previous_employers.*.industry'      => 'nullable|string|max:100',
            'previous_employers.*.responsibilities' => 'nullable|string|max:5000',
            'previous_employers.*.started_on'    => 'nullable|date',
            'previous_employers.*.ended_on'      => 'nullable|date',

            'passport.passport_number'           => 'nullable|string|max:50',
            'passport.passport_expiry_date'      => 'nullable|date',
            'passport.passport_issuing_country'  => 'nullable|string|max:100',
            'passport.visa_status'               => 'nullable|string|max:50',
            'passport.visa_type'                 => 'nullable|string|max:100',
            'passport.visa_expiry_date'          => 'nullable|date',

            'driving.license_number'             => 'nullable|string|max:50',
            'driving.license_country'            => 'nullable|string|max:100',
            'driving.license_type'               => 'nullable|string|max:50',
            'driving.license_expiry_date'        => 'nullable|date',

            'files.photo'                        => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'files.resume'                       => 'required|file|mimes:pdf,doc,docx|max:10240',

            'additional.cover_letter'            => 'nullable|string|max:5000',
            'additional.willing_to_relocate'     => 'nullable|boolean',
            'additional.willing_to_travel'       => 'nullable|boolean',
            'additional.interview_availability'  => 'nullable|string|max:255',
            'additional.linkedin_url'            => 'nullable|url|max:500',
            'additional.portfolio_url'           => 'nullable|url|max:500',
            'additional.reference_name'          => 'nullable|string|max:255',
            'additional.reference_contact'       => 'nullable|string|max:50',
            'additional.additional_comments'     => 'nullable|string|max:5000',

            'declared_information_accurate'      => 'accepted',
            'declared_authorize_verification'    => 'accepted',
            'declared_data_consent'              => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'personal.first_name.required'            => 'First name is required.',
            'personal.last_name.required'             => 'Last name is required.',
            'personal.nationality.required'            => 'Nationality is required.',
            'personal.email.required'                 => 'Email address is required.',
            'personal.mobile.required'                => 'Mobile number is required.',
            'job.position_applying_for.required'      => 'Position applying for is required.',
            'job.preferred_job_category.required'     => 'Preferred job category is required.',
            'education.qualification_level.required'  => 'Highest qualification is required.',
            'employment.total_years_experience.required' => 'Total years of experience is required.',
            'files.photo.required'                     => 'A recent photograph is required.',
            'files.photo.mimes'                        => 'Photo must be a JPG or PNG file.',
            'files.photo.max'                          => 'Photo must not exceed 2MB.',
            'files.resume.required'                    => 'Resume/CV is required.',
            'files.resume.mimes'                       => 'Resume must be a PDF, DOC, or DOCX file.',
            'files.resume.max'                         => 'Resume must not exceed 5MB.',
            'declared_information_accurate.accepted'   => 'You must confirm that the information is accurate.',
            'declared_authorize_verification.accepted' => 'You must authorize verification.',
            'declared_data_consent.accepted'           => 'You must consent to data processing.',
        ];
    }
}
