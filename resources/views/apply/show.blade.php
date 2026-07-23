<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apply — {{ $jobPosting->title }} | ABN Corporation</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .apply-body { background: var(--gray-bg); min-height: 100vh; }
        .apply-header { background: var(--navy-deep); color: #fff; padding: 1rem 2rem; display: flex; align-items: center; gap: 1rem; }
        .apply-header svg { flex-shrink: 0; }
        .apply-header h1 { font-size: 1.1rem; font-weight: 700; margin: 0; }
        .apply-header .apply-subtitle { font-size: 0.8rem; color: rgba(255,255,255,0.6); }
        .apply-container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .apply-container h2 { font-size: 1.3rem; color: var(--navy-deep); margin-bottom: 0.3rem; }
        .apply-container .job-meta { font-size: 0.85rem; color: var(--gray-text); margin-bottom: 1.5rem; }
        .progress-steps { display: flex; gap: 0.3rem; margin-bottom: 1.5rem; overflow-x: auto; padding-bottom: 0.3rem; }
        .progress-step { flex: 1; min-width: 60px; text-align: center; font-size: 0.65rem; color: var(--gray-text); position: relative; cursor: pointer; }
        .progress-step .step-num { display: block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background: var(--gray-border); color: #fff; font-size: 0.7rem; font-weight: 700; margin: 0 auto 0.2rem; transition: background 0.3s; }
        .progress-step.active .step-num { background: var(--blue-corporate); }
        .progress-step.done .step-num { background: var(--success); }
        .section-card { margin-bottom: 1.2rem; display: none; }
        .section-card.active { display: block; }
        .section-card h3 { font-size: 1rem; font-weight: 700; color: var(--navy-deep); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem; }
        .section-card h3 .section-num { background: var(--blue-corporate); color: #fff; width: 22px; height: 22px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.7rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.7rem 1rem; }
        .form-grid .form-full { grid-column: 1 / -1; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 0.78rem; font-weight: 600; color: var(--navy-deep); margin-bottom: 0.2rem; }
        .form-group label .required { color: var(--danger); }
        .form-group input, .form-group select, .form-group textarea { padding: 0.5rem 0.7rem; border: 1.5px solid var(--gray-border); border-radius: var(--radius-sm); font-size: 0.85rem; font-family: var(--font-family); transition: border-color 0.2s; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--blue-corporate); outline: none; }
        .form-group .field-error { color: var(--danger); font-size: 0.75rem; margin-top: 0.15rem; }
        .file-upload { border: 2px dashed var(--gray-border); border-radius: var(--radius-sm); padding: 1rem; text-align: center; cursor: pointer; transition: border-color 0.2s; }
        .file-upload:hover { border-color: var(--blue-corporate); }
        .file-upload.has-file { border-color: var(--success); background: #f0faf0; }
        .file-upload input[type="file"] { display: none; }
        .file-upload .upload-icon { font-size: 1.5rem; color: var(--gray-text); margin-bottom: 0.3rem; }
        .file-upload .upload-text { font-size: 0.8rem; color: var(--gray-text); }
        .file-upload .file-name { font-size: 0.8rem; color: var(--success); font-weight: 600; }
        .checkbox-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .checkbox-group label { display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.85rem; color: var(--navy-deep); cursor: pointer; }
        .checkbox-group input[type="checkbox"] { margin-top: 0.15rem; width: 16px; height: 16px; accent-color: var(--blue-corporate); }
        .repeat-row { display: flex; gap: 0.5rem; align-items: flex-end; margin-bottom: 0.5rem; }
        .repeat-row .form-group { flex: 1; }
        .repeat-row .btn-remove { background: none; border: none; color: var(--danger); cursor: pointer; font-size: 1rem; padding: 0.5rem; }
        .btn-add-row { background: none; border: 1px dashed var(--gray-border); color: var(--blue-corporate); padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); font-size: 0.8rem; cursor: pointer; margin-top: 0.3rem; }
        .btn-add-row:hover { border-color: var(--blue-corporate); background: #f0f5ff; }
        .step-nav { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-bottom: 2rem; gap: 0.7rem; }
        .step-nav .btn { padding: 0.6rem 1.5rem; }
        .apply-footer { text-align: center; padding: 1rem; font-size: 0.75rem; color: var(--gray-text); border-top: 1px solid var(--gray-border); margin-top: 2rem; }
        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .repeat-row { flex-direction: column; align-items: stretch; }
            .progress-step { min-width: 40px; font-size: 0.55rem; }
        }
    </style>
</head>
<body class="apply-body">
    <div class="apply-header">
        <svg width="32" height="32" viewBox="0 0 48 48" fill="none">
            <rect width="48" height="48" rx="8" fill="#C8A94E"/>
            <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#0F1B2D" font-size="16" font-weight="700" font-family="sans-serif">ABN</text>
        </svg>
        <div>
            <h1>ABN Corporation — Job Application</h1>
            <div class="apply-subtitle">Enterprise Recruitment Management System</div>
        </div>
    </div>

    <div class="apply-container">
        @if (session('success'))
            <div class="card" style="text-align:center;padding:3rem 2rem;">
                <i class="fa-solid fa-circle-check" style="font-size:3rem;color:var(--success);margin-bottom:1rem;"></i>
                <h2 style="color:var(--navy-deep);">Application Submitted!</h2>
                <p style="color:var(--gray-text);margin-top:0.5rem;">{{ session('success') }}</p>
                <a href="/" class="btn btn-outline" style="margin-top:1.5rem;"><i class="fa-solid fa-house"></i> Return to Home</a>
            </div>
        @else
            <h2>Apply for {{ $jobPosting->title }}</h2>
            <div class="job-meta">
                {{ $jobPosting->groupCompany?->name ?? '—' }} &mdash; {{ $jobPosting->location ?? '—' }}
                &bull; {{ $jobPosting->employment_type ? str_replace('_', ' ', ucfirst($jobPosting->employment_type)) : '—' }}
                @if ($jobPosting->deadline) &bull; Deadline: {{ $jobPosting->deadline->format('M d, Y') }} @endif
            </div>

            <div class="progress-steps" id="progressSteps">
                <div class="progress-step active" data-step="1" onclick="goToStep(1)"><span class="step-num">1</span>Personal</div>
                <div class="progress-step" data-step="2" onclick="goToStep(2)"><span class="step-num">2</span>Job</div>
                <div class="progress-step" data-step="3" onclick="goToStep(3)"><span class="step-num">3</span>Education</div>
                <div class="progress-step" data-step="4" onclick="goToStep(4)"><span class="step-num">4</span>Experience</div>
                <div class="progress-step" data-step="5" onclick="goToStep(5)"><span class="step-num">5</span>Employers</div>
                <div class="progress-step" data-step="6" onclick="goToStep(6)"><span class="step-num">6</span>Passport</div>
                <div class="progress-step" data-step="7" onclick="goToStep(7)"><span class="step-num">7</span>License</div>
                <div class="progress-step" data-step="8" onclick="goToStep(8)"><span class="step-num">8</span>Docs</div>
                <div class="progress-step" data-step="9" onclick="goToStep(9)"><span class="step-num">9</span>Additional</div>
                <div class="progress-step" data-step="10" onclick="goToStep(10)"><span class="step-num">10</span>Declare</div>
            </div>

            <form method="POST" action="{{ route('apply.store', $jobPosting) }}" enctype="multipart/form-data" id="applyForm">
                @csrf

                {{-- Section 1: Personal Details --}}
                <div class="card section-card active" data-section="1">
                    <h3><span class="section-num">1</span> Personal Details</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>First Name <span class="required">*</span></label>
                            <input type="text" name="personal[first_name]" value="{{ old('personal.first_name') }}" required>
                            @error('personal.first_name')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Last Name <span class="required">*</span></label>
                            <input type="text" name="personal[last_name]" value="{{ old('personal.last_name') }}" required>
                            @error('personal.last_name')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="personal[gender]">
                                <option value="">Select</option>
                                <option value="Male" {{ old('personal.gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('personal.gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="personal[date_of_birth]" value="{{ old('personal.date_of_birth') }}">
                            @error('personal.date_of_birth')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Nationality <span class="required">*</span></label>
                            <select name="personal[nationality]" required>
                                <option value="">Select Nationality</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" {{ old('personal.nationality') === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('personal.nationality')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Marital Status</label>
                            <select name="personal[marital_status]">
                                <option value="">Select</option>
                                <option value="Single" {{ old('personal.marital_status') === 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('personal.marital_status') === 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('personal.marital_status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('personal.marital_status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="personal[email]" value="{{ old('personal.email') }}" required>
                            @error('personal.email')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Mobile <span class="required">*</span></label>
                            <div style="display:flex;gap:0.3rem;">
                                <select name="personal[mobile_code]" style="width:100px;flex-shrink:0;padding:0.5rem 0.7rem;border:1.5px solid var(--gray-border);border-radius:var(--radius-sm);font-size:0.85rem;">
                                    <option value="">Code</option>
                                    @foreach ($countries as $country)
                                        <option value="+{{ $country->phone_code }}" {{ old('personal.mobile_code') === '+'.$country->phone_code ? 'selected' : '' }}>+{{ $country->phone_code }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="personal[mobile]" value="{{ old('personal.mobile') }}" required placeholder="501234567" style="flex:1;padding:0.5rem 0.7rem;border:1.5px solid var(--gray-border);border-radius:var(--radius-sm);font-size:0.85rem;">
                            </div>
                            @error('personal.mobile')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Alternate Contact</label>
                            <div style="display:flex;gap:0.3rem;">
                                <select name="personal[alternate_code]" style="width:100px;flex-shrink:0;padding:0.5rem 0.7rem;border:1.5px solid var(--gray-border);border-radius:var(--radius-sm);font-size:0.85rem;">
                                    <option value="">Code</option>
                                    @foreach ($countries as $country)
                                        <option value="+{{ $country->phone_code }}" {{ old('personal.alternate_code') === '+'.$country->phone_code ? 'selected' : '' }}>+{{ $country->phone_code }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="personal[alternate_contact]" value="{{ old('personal.alternate_contact') }}" placeholder="501234567" style="flex:1;padding:0.5rem 0.7rem;border:1.5px solid var(--gray-border);border-radius:var(--radius-sm);font-size:0.85rem;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Current Country</label>
                            <select name="personal[current_country]">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" {{ old('personal.current_country') === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Current City</label>
                            <input type="text" name="personal[current_city]" value="{{ old('personal.current_city') }}">
                        </div>
                        <div class="form-group">
                            <label>Postal/ZIP Code</label>
                            <input type="text" name="personal[postal_code]" value="{{ old('personal.postal_code') }}">
                        </div>
                        <div class="form-group form-full">
                            <label>Current Address</label>
                            <textarea name="personal[current_address]" rows="2">{{ old('personal.current_address') }}</textarea>
                        </div>
                        <div class="form-group form-full">
                            <label>Permanent Address</label>
                            <textarea name="personal[permanent_address]" rows="2">{{ old('personal.permanent_address') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Job Details --}}
                <div class="card section-card" data-section="2">
                    <h3><span class="section-num">2</span> Job Details &amp; Preferences</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Position Applying For <span class="required">*</span></label>
                            <input type="text" name="job[position_applying_for]" value="{{ old('job.position_applying_for', $jobPosting->title) }}" required>
                            @error('job.position_applying_for')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Preferred Job Category <span class="required">*</span></label>
                            <input type="text" name="job[preferred_job_category]" value="{{ old('job.preferred_job_category') }}" required>
                            @error('job.preferred_job_category')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Current Job Title</label>
                            <input type="text" name="job[current_job_title]" value="{{ old('job.current_job_title') }}">
                        </div>
                        <div class="form-group">
                            <label>Current Employer</label>
                            <input type="text" name="job[current_employer]" value="{{ old('job.current_employer') }}">
                        </div>
                        <div class="form-group">
                            <label>Employment Status</label>
                            <select name="job[employment_status]">
                                <option value="">Select</option>
                                <option value="Employed" {{ old('job.employment_status') === 'Employed' ? 'selected' : '' }}>Employed</option>
                                <option value="Self-Employed" {{ old('job.employment_status') === 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                                <option value="Unemployed" {{ old('job.employment_status') === 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                                <option value="Student" {{ old('job.employment_status') === 'Student' ? 'selected' : '' }}>Student</option>
                                <option value="Notice Period" {{ old('job.employment_status') === 'Notice Period' ? 'selected' : '' }}>Notice Period</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Notice Period</label>
                            <input type="text" name="job[notice_period]" value="{{ old('job.notice_period') }}" placeholder="e.g. 30 days">
                        </div>
                        <div class="form-group form-full">
                            <label>Available to Join From</label>
                            <input type="date" name="job[available_to_join_from]" value="{{ old('job.available_to_join_from') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 3: Education --}}
                <div class="card section-card" data-section="3">
                    <h3><span class="section-num">3</span> Education History</h3>
                    <div id="educationContainer">
                        <div class="form-grid education-row">
                            <div class="form-group">
                                <label>Highest Qualification <span class="required">*</span></label>
                                <select name="education[qualification_level]" required>
                                    <option value="">Select</option>
                                    <option value="High School" {{ old('education.qualification_level') === 'High School' ? 'selected' : '' }}>High School</option>
                                    <option value="Diploma" {{ old('education.qualification_level') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Bachelor's Degree" {{ old('education.qualification_level') === "Bachelor's Degree" ? 'selected' : '' }}>Bachelor's Degree</option>
                                    <option value="Master's Degree" {{ old('education.qualification_level') === "Master's Degree" ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="Doctorate" {{ old('education.qualification_level') === 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                                </select>
                                @error('education.qualification_level')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label>Degree / Diploma</label>
                                <input type="text" name="education[degree_diploma]" value="{{ old('education.degree_diploma') }}">
                            </div>
                            <div class="form-group">
                                <label>Specialization / Major</label>
                                <input type="text" name="education[specialization]" value="{{ old('education.specialization') }}">
                            </div>
                            <div class="form-group">
                                <label>Institution</label>
                                <input type="text" name="education[institution]" value="{{ old('education.institution') }}">
                            </div>
                            <div class="form-group">
                                <label>Graduation Year</label>
                                <input type="number" name="education[graduation_year]" value="{{ old('education.graduation_year') }}" min="1950" max="2100" placeholder="YYYY">
                            </div>
                            <div class="form-group">
                                <label>Percentage / CGPA</label>
                                <input type="text" name="education[grade_percentage]" value="{{ old('education.grade_percentage') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Employment --}}
                <div class="card section-card" data-section="4">
                    <h3><span class="section-num">4</span> Employment Summary</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Total Years of Experience <span class="required">*</span></label>
                            <input type="number" name="employment[total_years_experience]" value="{{ old('employment.total_years_experience') }}" step="0.1" min="0" max="99" required>
                            @error('employment.total_years_experience')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Current Designation</label>
                            <input type="text" name="employment[current_designation]" value="{{ old('employment.current_designation') }}">
                        </div>
                        <div class="form-group">
                            <label>Industry</label>
                            <input type="text" name="employment[industry]" value="{{ old('employment.industry') }}">
                        </div>
                        <div class="form-group">
                            <label>Last Working Date</label>
                            <input type="date" name="employment[last_working_date]" value="{{ old('employment.last_working_date') }}">
                        </div>
                        <div class="form-group form-full">
                            <label>Key Responsibilities</label>
                            <textarea name="employment[key_responsibilities]" rows="3">{{ old('employment.key_responsibilities') }}</textarea>
                        </div>
                        <div class="form-group form-full">
                            <label>Relevant Experience</label>
                            <textarea name="employment[relevant_experience]" rows="3">{{ old('employment.relevant_experience') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 5: Previous Employers --}}
                <div class="card section-card" data-section="5">
                    <h3><span class="section-num">5</span> Previous Employers</h3>
                    <div id="previousEmployersContainer">
                        <div class="previous-employer-row repeat-row">
                            <div class="form-group">
                                <label>Employer Name</label>
                                <input type="text" name="previous_employers[0][employer_name]" value="{{ old('previous_employers.0.employer_name') }}">
                            </div>
                            <div class="form-group">
                                <label>Designation</label>
                                <input type="text" name="previous_employers[0][designation]" value="{{ old('previous_employers.0.designation') }}">
                            </div>
                            <div class="form-group">
                                <label>Industry</label>
                                <input type="text" name="previous_employers[0][industry]" value="{{ old('previous_employers.0.industry') }}">
                            </div>
                            <div class="form-group" style="flex:0.5;">
                                <label>Start</label>
                                <input type="date" name="previous_employers[0][started_on]" value="{{ old('previous_employers.0.started_on') }}">
                            </div>
                            <div class="form-group" style="flex:0.5;">
                                <label>End</label>
                                <input type="date" name="previous_employers[0][ended_on]" value="{{ old('previous_employers.0.ended_on') }}">
                            </div>
                            <button type="button" class="btn-remove" onclick="this.parentElement.remove()" style="display:none;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" class="btn-add-row" onclick="addEmployerRow()"><i class="fa-solid fa-plus"></i> Add Another Employer</button>
                </div>

                {{-- Section 6: Passport & Visa --}}
                <div class="card section-card" data-section="6">
                    <h3><span class="section-num">6</span> Passport &amp; Visa</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Passport Number</label>
                            <input type="text" name="passport[passport_number]" value="{{ old('passport.passport_number') }}">
                        </div>
                        <div class="form-group">
                            <label>Passport Expiry Date</label>
                            <input type="date" name="passport[passport_expiry_date]" value="{{ old('passport.passport_expiry_date') }}">
                        </div>
                        <div class="form-group">
                            <label>Passport Issuing Country</label>
                            <select name="passport[passport_issuing_country]">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" {{ old('passport.passport_issuing_country') === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Visa Status</label>
                            <select name="passport[visa_status]">
                                <option value="">Select</option>
                                <option value="Employment Visa" {{ old('passport.visa_status') === 'Employment Visa' ? 'selected' : '' }}>Employment Visa</option>
                                <option value="Visit Visa" {{ old('passport.visa_status') === 'Visit Visa' ? 'selected' : '' }}>Visit Visa</option>
                                <option value="Resident Visa" {{ old('passport.visa_status') === 'Resident Visa' ? 'selected' : '' }}>Resident Visa</option>
                                <option value="Student Visa" {{ old('passport.visa_status') === 'Student Visa' ? 'selected' : '' }}>Student Visa</option>
                                <option value="Citizen" {{ old('passport.visa_status') === 'Citizen' ? 'selected' : '' }}>Citizen</option>
                                <option value="None" {{ old('passport.visa_status') === 'None' ? 'selected' : '' }}>None</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Visa Type</label>
                            <input type="text" name="passport[visa_type]" value="{{ old('passport.visa_type') }}">
                        </div>
                        <div class="form-group">
                            <label>Visa Expiry Date</label>
                            <input type="date" name="passport[visa_expiry_date]" value="{{ old('passport.visa_expiry_date') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 7: Driving License --}}
                <div class="card section-card" data-section="7">
                    <h3><span class="section-num">7</span> Driving License</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>License Number</label>
                            <input type="text" name="driving[license_number]" value="{{ old('driving.license_number') }}">
                        </div>
                        <div class="form-group">
                            <label>License Country</label>
                            <select name="driving[license_country]">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" {{ old('driving.license_country') === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>License Type</label>
                            <input type="text" name="driving[license_type]" value="{{ old('driving.license_type') }}">
                        </div>
                        <div class="form-group">
                            <label>License Expiry Date</label>
                            <input type="date" name="driving[license_expiry_date]" value="{{ old('driving.license_expiry_date') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 8: Documents --}}
                <div class="card section-card" data-section="8">
                    <h3><span class="section-num">8</span> Documents</h3>
                    <div class="form-grid">
                        <div class="form-group form-full">
                            <label>Recent Photograph <span class="required">*</span> <span style="font-weight:400;color:var(--gray-text);font-size:0.75rem;">(JPG/PNG, max 2MB)</span></label>
                            <div class="file-upload" onclick="document.getElementById('photoInput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-camera"></i></div>
                                <div class="upload-text" id="photoText">Click to upload photo</div>
                                <div class="file-name" id="photoName" style="display:none;"></div>
                            </div>
                            <input type="file" id="photoInput" name="files[photo]" accept=".jpg,.jpeg,.png" onchange="handleFileSelect(this, 'photoText', 'photoName')" required>
                            @error('files.photo')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group form-full">
                            <label>Resume / CV <span class="required">*</span> <span style="font-weight:400;color:var(--gray-text);font-size:0.75rem;">(PDF/DOC/DOCX, max 5MB)</span></label>
                            <div class="file-upload" onclick="document.getElementById('resumeInput').click()">
                                <div class="upload-icon"><i class="fa-solid fa-file"></i></div>
                                <div class="upload-text" id="resumeText">Click to upload resume</div>
                                <div class="file-name" id="resumeName" style="display:none;"></div>
                            </div>
                            <input type="file" id="resumeInput" name="files[resume]" accept=".pdf,.doc,.docx" onchange="handleFileSelect(this, 'resumeText', 'resumeName')" required>
                            @error('files.resume')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                {{-- Section 9: Additional Info --}}
                <div class="card section-card" data-section="9">
                    <h3><span class="section-num">9</span> Additional Information</h3>
                    <div class="form-grid">
                        <div class="form-group form-full">
                            <label>Cover Letter</label>
                            <textarea name="additional[cover_letter]" rows="4">{{ old('additional.cover_letter') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Willing to Relocate</label>
                            <select name="additional[willing_to_relocate]">
                                <option value="">Select</option>
                                <option value="1" {{ old('additional.willing_to_relocate') ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('additional.willing_to_relocate') && old('additional.willing_to_relocate') !== null ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Willing to Travel</label>
                            <select name="additional[willing_to_travel]">
                                <option value="">Select</option>
                                <option value="1" {{ old('additional.willing_to_travel') ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('additional.willing_to_travel') && old('additional.willing_to_travel') !== null ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group form-full">
                            <label>Interview Availability</label>
                            <input type="text" name="additional[interview_availability]" value="{{ old('additional.interview_availability') }}" placeholder="e.g. Weekdays after 2 PM">
                        </div>
                        <div class="form-group">
                            <label>LinkedIn URL</label>
                            <input type="url" name="additional[linkedin_url]" value="{{ old('additional.linkedin_url') }}">
                        </div>
                        <div class="form-group">
                            <label>Portfolio / Website</label>
                            <input type="url" name="additional[portfolio_url]" value="{{ old('additional.portfolio_url') }}">
                        </div>
                        <div class="form-group">
                            <label>Reference Name</label>
                            <input type="text" name="additional[reference_name]" value="{{ old('additional.reference_name') }}">
                        </div>
                        <div class="form-group">
                            <label>Reference Contact</label>
                            <input type="text" name="additional[reference_contact]" value="{{ old('additional.reference_contact') }}">
                        </div>
                        <div class="form-group form-full">
                            <label>Additional Comments</label>
                            <textarea name="additional[additional_comments]" rows="3">{{ old('additional.additional_comments') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 10: Declarations --}}
                <div class="card section-card" data-section="10">
                    <h3><span class="section-num">10</span> Declarations &amp; Consent</h3>
                    <div class="checkbox-group">
                        <label>
                            <input type="checkbox" name="declared_information_accurate" value="1" {{ old('declared_information_accurate') ? 'checked' : '' }} required>
                            I confirm that all information provided is accurate and complete to the best of my knowledge.
                        </label>
                        @error('declared_information_accurate')<span class="field-error">{{ $message }}</span>@enderror
                        <label>
                            <input type="checkbox" name="declared_authorize_verification" value="1" {{ old('declared_authorize_verification') ? 'checked' : '' }} required>
                            I authorize ABN Corporation to verify the information provided and conduct background checks.
                        </label>
                        @error('declared_authorize_verification')<span class="field-error">{{ $message }}</span>@enderror
                        <label>
                            <input type="checkbox" name="declared_data_consent" value="1" {{ old('declared_data_consent') ? 'checked' : '' }} required>
                            I consent to the processing and retention of my personal data for recruitment purposes.
                        </label>
                        @error('declared_data_consent')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="step-nav" id="stepNav">
                    <button type="button" class="btn btn-outline" id="prevBtn" onclick="prevStep()" style="visibility:hidden;">
                        <i class="fa-solid fa-arrow-left"></i> Previous
                    </button>
                    <span id="stepCounter" style="font-size:0.8rem;color:var(--gray-text);">Step 1 of 10</span>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">
                        Next <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn" style="display:none;">
                        <i class="fa-solid fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </form>
        @endif
    </div>

    <div class="apply-footer">
        &copy; {{ date('Y') }} ABN Corporation. All rights reserved.
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 10;

        function goToStep(step) {
            if (step < 1 || step > totalSteps) return;
            if (step > currentStep) {
                const currentSection = document.querySelector(`.section-card[data-section="${currentStep}"]`);
                const required = currentSection.querySelectorAll('[required]');
                for (const el of required) {
                    if (!el.checkValidity()) {
                        el.reportValidity();
                        return;
                    }
                }
            }
            showStep(step);
        }

        function showStep(step) {
            currentStep = step;
            document.querySelectorAll('.section-card').forEach(el => el.classList.remove('active'));
            document.querySelector(`.section-card[data-section="${step}"]`).classList.add('active');
            document.querySelectorAll('.progress-step').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.progress-step').forEach(el => {
                el.classList.toggle('done', parseInt(el.dataset.step) < step);
            });
            document.querySelector(`.progress-step[data-step="${step}"]`).classList.add('active');
            document.getElementById('stepCounter').textContent = `Step ${step} of ${totalSteps}`;
            document.getElementById('prevBtn').style.visibility = step === 1 ? 'hidden' : 'visible';
            if (step === totalSteps) {
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('submitBtn').style.display = '';
            } else {
                document.getElementById('nextBtn').style.display = '';
                document.getElementById('submitBtn').style.display = 'none';
            }
        }

        function nextStep() {
            const currentSection = document.querySelector(`.section-card[data-section="${currentStep}"]`);
            const required = currentSection.querySelectorAll('[required]');
            for (const el of required) {
                if (!el.checkValidity()) {
                    el.reportValidity();
                    return;
                }
            }
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        }

        function handleFileSelect(input, textId, nameId) {
            const textEl = document.getElementById(textId);
            const nameEl = document.getElementById(nameId);
            const uploadEl = input.closest('.form-group').querySelector('.file-upload');
            if (input.files && input.files[0]) {
                textEl.style.display = 'none';
                nameEl.style.display = 'block';
                nameEl.textContent = input.files[0].name;
                uploadEl.classList.add('has-file');
            } else {
                textEl.style.display = 'block';
                nameEl.style.display = 'none';
                uploadEl.classList.remove('has-file');
            }
        }

        let employerIndex = 1;

        function addEmployerRow() {
            const container = document.getElementById('previousEmployersContainer');
            const row = document.createElement('div');
            row.className = 'previous-employer-row repeat-row';
            row.innerHTML = `
                <div class="form-group">
                    <label>Employer Name</label>
                    <input type="text" name="previous_employers[${employerIndex}][employer_name]">
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="previous_employers[${employerIndex}][designation]">
                </div>
                <div class="form-group">
                    <label>Industry</label>
                    <input type="text" name="previous_employers[${employerIndex}][industry]">
                </div>
                <div class="form-group" style="flex:0.5;">
                    <label>Start</label>
                    <input type="date" name="previous_employers[${employerIndex}][started_on]">
                </div>
                <div class="form-group" style="flex:0.5;">
                    <label>End</label>
                    <input type="date" name="previous_employers[${employerIndex}][ended_on]">
                </div>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()"><i class="fa-solid fa-trash"></i></button>
            `;
            container.appendChild(row);
            employerIndex++;
        }

        document.querySelectorAll('.previous-employer-row input').forEach(input => {
            input.addEventListener('input', function () {
                const removeBtn = this.closest('.previous-employer-row').querySelector('.btn-remove');
                if (removeBtn) removeBtn.style.display = '';
            });
        });

        document.getElementById('applyForm').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.tagName === 'TEXTAREA') return;
                e.preventDefault();
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn.style.display !== 'none') {
                    submitBtn.click();
                } else {
                    nextStep();
                }
            }
        });
    </script>
</body>
</html>
