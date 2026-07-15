<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Home</a> <span>/</span>
                    <a href="{{ route('applications.index') }}">Applications</a> <span>/</span>
                    <span>Candidate Profile</span>
                </div>
                <h2>{{ $application->candidate_display_name ?? 'Candidate #' . $application->id }}</h2>
                <p>Applied for: {{ $application->jobPosting?->title ?? '—' }} — {{ $application->jobPosting?->groupCompany?->name ?? '—' }}</p>
            </div>
        </div>
    </x-slot>

    {{-- Summary --}}
    <div class="card section-card">
        <h3><i class="fa-solid fa-address-card"></i> Summary</h3>
        <div class="detail-grid">
            <x-detail-item label="Application ID" :value="$application->code ?? '#' . $application->id" />
            <x-detail-item label="Full Name" :value="$application->candidate_display_name ?? '—'" />
            <x-detail-item label="Job Title" :value="$application->jobPosting?->title ?? '—'" />
            <x-detail-item label="Group Company" :value="$application->jobPosting?->groupCompany?->name ?? '—'" />
            <x-detail-item label="Email" :value="$application->email ?? '—'" />
            <x-detail-item label="Mobile" :value="$application->mobile ?? '—'" />
            <x-detail-item label="Status" :value="view('components.status-badge', ['status' => $application->status ?? 'pending'])->render()" />
            <x-detail-item label="Applied Date" :value="$application->submitted_at?->format('M d, Y') ?? '—'" />
            <x-detail-item label="Assigned To" :value="$application->assignedTo?->name ?? '—'" />
            <x-detail-item label="Reviewed By" :value="$application->reviewedBy?->name ?? '—'" />
        </div>
    </div>

    <div class="grid-2">
        {{-- Personal Details --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-user"></i> Personal Details</h3>
            @php $pd = $application->personalDetails; @endphp
            @if ($pd)
                <div class="detail-grid">
                    <x-detail-item label="First Name" :value="$pd->first_name" />
                    <x-detail-item label="Last Name" :value="$pd->last_name" />
                    <x-detail-item label="Full Name" :value="$pd->full_name" />
                    <x-detail-item label="Gender" :value="$pd->gender" />
                    <x-detail-item label="Date of Birth" :value="$pd->date_of_birth" />
                    <x-detail-item label="Nationality" :value="$pd->nationality" />
                    <x-detail-item label="Marital Status" :value="$pd->marital_status" />
                    <x-detail-item label="Email" :value="$pd->email" />
                    <x-detail-item label="Mobile" :value="$pd->mobile" />
                    <x-detail-item label="Alternate Contact" :value="$pd->alternate_contact" />
                    <x-detail-item label="Current Address" :value="$pd->current_address" />
                    <x-detail-item label="Permanent Address" :value="$pd->permanent_address" />
                    <x-detail-item label="Current Country" :value="$pd->current_country" />
                    <x-detail-item label="Current City" :value="$pd->current_city" />
                    <x-detail-item label="Postal Code" :value="$pd->postal_code" />
                </div>
            @else
                <div class="empty-state">No personal details provided.</div>
            @endif
        </div>

        {{-- Job Details --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-briefcase"></i> Job Details</h3>
            @php $jd = $application->jobDetails; @endphp
            @if ($jd)
                <div class="detail-grid">
                    <x-detail-item label="Position Applying For" :value="$jd->position_applying_for" />
                    <x-detail-item label="Preferred Job Category" :value="$jd->preferred_job_category" />
                    <x-detail-item label="Current Job Title" :value="$jd->current_job_title" />
                    <x-detail-item label="Current Employer" :value="$jd->current_employer" />
                    <x-detail-item label="Employment Status" :value="$jd->employment_status" />
                    <x-detail-item label="Notice Period" :value="$jd->notice_period" />
                    <x-detail-item label="Available to Join From" :value="$jd->available_to_join_from" />
                </div>
            @else
                <div class="empty-state">No job details provided.</div>
            @endif
        </div>
    </div>

    {{-- Education --}}
    <div class="card section-card">
        <h3><i class="fa-solid fa-graduation-cap"></i> Education</h3>
        @php $educations = $application->educations; @endphp
        @if ($educations && $educations->isNotEmpty())
            <div class="table-container" style="margin-top:0.8rem;">
                <table>
                    <thead><tr><th>Level</th><th>Degree</th><th>Specialization</th><th>Institution</th><th>Year</th><th>Grade</th></tr></thead>
                    <tbody>
                        @foreach ($educations as $edu)
                            <tr>
                                <td>{{ $edu->qualification_level ?? '—' }}</td>
                                <td>{{ $edu->degree_diploma ?? '—' }}</td>
                                <td>{{ $edu->specialization ?? '—' }}</td>
                                <td>{{ $edu->institution ?? '—' }}</td>
                                <td>{{ $edu->graduation_year ?? '—' }}</td>
                                <td>{{ $edu->grade_percentage ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">No education details provided.</div>
        @endif
    </div>

    <div class="grid-2">
        {{-- Employment Summary --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-chart-line"></i> Employment Summary</h3>
            @php $emp = $application->employment; @endphp
            @if ($emp)
                <div class="detail-grid">
                    <x-detail-item label="Total Years Experience" :value="$emp->total_years_experience ? $emp->total_years_experience . ' yrs' : '—'" />
                    <x-detail-item label="Current Designation" :value="$emp->current_designation" />
                    <x-detail-item label="Industry" :value="$emp->industry" />
                    <x-detail-item label="Key Responsibilities" :value="$emp->key_responsibilities" />
                    <x-detail-item label="Relevant Experience" :value="$emp->relevant_experience" />
                    <x-detail-item label="Last Working Date" :value="$emp->last_working_date ?? '—'" />
                </div>
            @else
                <div class="empty-state">No employment details provided.</div>
            @endif
        </div>

        {{-- Previous Employers --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-history"></i> Previous Employers</h3>
            @php $prev = $application->previousEmployers; @endphp
            @if ($prev && $prev->isNotEmpty())
                @foreach ($prev as $pe)
                    <div class="note-item">
                        <strong>{{ $pe->employer_name ?? '—' }}</strong> — {{ $pe->designation ?? '—' }}
                        <div class="note-meta">{{ $pe->industry ?? '—' }} | {{ $pe->started_on ?? '—' }} — {{ $pe->ended_on ?? 'Present' }}{{ $pe->responsibilities ? '<br>' . e($pe->responsibilities) : '' }}</div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">No previous employment history provided.</div>
            @endif
        </div>
    </div>

    <div class="grid-2">
        {{-- Passport & Visa --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-passport"></i> Passport & Visa</h3>
            @php $pv = $application->passportVisa; @endphp
            @if ($pv)
                <div class="detail-grid">
                    <x-detail-item label="Passport Number" :value="$pv->passport_number" />
                    <x-detail-item label="Passport Expiry" :value="$pv->passport_expiry_date" />
                    <x-detail-item label="Issuing Country" :value="$pv->passport_issuing_country" />
                    <x-detail-item label="Visa Status" :value="$pv->visa_status" />
                    <x-detail-item label="Visa Type" :value="$pv->visa_type" />
                    <x-detail-item label="Visa Expiry" :value="$pv->visa_expiry_date" />
                </div>
            @else
                <div class="empty-state">No passport/visa details provided.</div>
            @endif
        </div>

        {{-- Driving License --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-car"></i> Driving License</h3>
            @php $dl = $application->drivingLicense; @endphp
            @if ($dl)
                <div class="detail-grid">
                    <x-detail-item label="License Number" :value="$dl->license_number" />
                    <x-detail-item label="License Country" :value="$dl->license_country" />
                    <x-detail-item label="License Type" :value="$dl->license_type" />
                    <x-detail-item label="License Expiry" :value="$dl->license_expiry_date" />
                </div>
            @else
                <div class="empty-state">No driving license details provided.</div>
            @endif
        </div>
    </div>

    {{-- Documents --}}
    <div class="card section-card">
        <h3><i class="fa-solid fa-file"></i> Documents</h3>
        @php $docs = $application->documents; @endphp
        @if ($docs && $docs->isNotEmpty())
            <div style="margin-top:0.8rem;display:flex;flex-wrap:wrap;gap:0.5rem;">
                @foreach ($docs as $doc)
                    <a href="{{ route('documents.download', $doc) }}" class="doc-chip" style="text-decoration:none;cursor:pointer;">
                        <i class="fa-solid {{ $doc->mime_type === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $doc->mime_type === 'application/pdf' ? '#C0392B' : '#2563EB' }};"></i>
                        {{ $doc->original_filename ?? '—' }}
                        <span class="text-muted">({{ $doc->file_size_bytes ? round($doc->file_size_bytes / 1024) . ' KB' : '—' }})</span>
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-state">No documents uploaded.</div>
        @endif
    </div>

    <div class="grid-2">
        {{-- Additional Info --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-circle-info"></i> Additional Info</h3>
            @php $ai = $application->additionalInfo; @endphp
            @if ($ai)
                <div class="detail-grid">
                    <x-detail-item label="Willing to Relocate" :value="$ai->willing_to_relocate ? 'Yes' : 'No'" />
                    <x-detail-item label="Willing to Travel" :value="$ai->willing_to_travel ? 'Yes' : 'No'" />
                    <x-detail-item label="Interview Availability" :value="$ai->interview_availability" />
                    <x-detail-item label="LinkedIn URL" :value="$ai->linkedin_url" />
                    <x-detail-item label="Portfolio URL" :value="$ai->portfolio_url" />
                    <x-detail-item label="Reference Name" :value="$ai->reference_name" />
                    <x-detail-item label="Reference Contact" :value="$ai->reference_contact" />
                    <x-detail-item label="Additional Comments" :value="$ai->additional_comments" />
                </div>
            @else
                <div class="empty-state">No additional information provided.</div>
            @endif
        </div>

        {{-- Declarations & Consent --}}
        <div class="card section-card">
            <h3><i class="fa-solid fa-check-circle"></i> Declarations & Consent</h3>
            <ul class="consent-list" style="margin-top:0.8rem;">
                <li><i class="fa-solid {{ $application->declared_information_accurate ? 'fa-circle-check' : 'fa-circle-xmark' }}" style="color:{{ $application->declared_information_accurate ? 'var(--success)' : 'var(--danger)' }}"></i> Information Accurate: {{ $application->declared_information_accurate ? 'Confirmed' : 'Not confirmed' }}</li>
                <li><i class="fa-solid {{ $application->declared_authorize_verification ? 'fa-circle-check' : 'fa-circle-xmark' }}" style="color:{{ $application->declared_authorize_verification ? 'var(--success)' : 'var(--danger)' }}"></i> Authorize Verification: {{ $application->declared_authorize_verification ? 'Authorized' : 'Not authorized' }}</li>
                <li><i class="fa-solid {{ $application->declared_data_consent ? 'fa-circle-check' : 'fa-circle-xmark' }}" style="color:{{ $application->declared_data_consent ? 'var(--success)' : 'var(--danger)' }}"></i> Data Consent: {{ $application->declared_data_consent ? 'Consented' : 'Not consented' }}</li>
                <li class="detail-value" style="margin-top:0.3rem;">{{ $application->declared_at ? 'Declared at: ' . $application->declared_at->format('M d, Y H:i') : 'Not yet declared' }}</li>
            </ul>
        </div>
    </div>

    {{-- Status History --}}
    <div class="card section-card">
        <h3><i class="fa-solid fa-clock-rotate-left"></i> Status History</h3>
        @php $history = $application->statusHistories; @endphp
        @if ($history && $history->isNotEmpty())
            <div class="table-container" style="margin-top:0.8rem;">
                <table>
                    <thead><tr><th>Previous</th><th>Current</th><th>Changed By</th><th>Date</th></tr></thead>
                    <tbody>
                        @foreach ($history as $h)
                            <tr>
                                <td>{!! $h->previous_status ? view('components.status-badge', ['status' => $h->previous_status])->render() : '<span class="text-muted">—</span>' !!}</td>
                                <td>{!! view('components.status-badge', ['status' => $h->current_status])->render() !!}</td>
                                <td>{{ $h->changedByUser?->name ?? '—' }}</td>
                                <td>{{ $h->changed_at?->format('M d, Y H:i') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">No status history recorded.</div>
        @endif
    </div>

    {{-- Internal Notes --}}
    <div class="card section-card">
        <h3><i class="fa-solid fa-note-sticky"></i> Internal Notes</h3>
        @php $notes = $application->notes; @endphp
        @if ($notes && $notes->isNotEmpty())
            <div style="margin-top:0.8rem;">
                @foreach ($notes as $note)
                    <div class="note-item">
                        <div>{{ e($note->note ?? '') }}{{ $note->visibility ? ' <span class="badge badge-draft">' . e($note->visibility) . '</span>' : '' }}</div>
                        <div class="note-meta">By {{ $note->user?->name ?? '—' }} — {{ $note->created_at?->format('M d, Y H:i') ?? '—' }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">No internal notes for this candidate.</div>
        @endif
    </div>

    {{-- Pipeline --}}
    @php
        $stages = ['pending', 'screening', 'interview', 'offer', 'hired'];
        $stageLabels = ['pending' => 'Applied', 'screening' => 'Screening', 'interview' => 'Interview', 'offer' => 'Offer', 'hired' => 'Hired'];
        $currentIdx = array_search($application->status, $stages);
    @endphp
    <div class="card section-card">
        <h3><i class="fa-solid fa-filter"></i> Hiring Pipeline</h3>
        @if ($application->status === 'rejected')
            <div class="table-container" style="margin-top:0.8rem;">
                <table>
                    <thead><tr><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td><span class="badge badge-rejected">Rejected</span> <span class="text-muted">This candidate is no longer in the active pipeline.</span></td></tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="table-container" style="margin-top:0.8rem;">
                <table>
                    <thead><tr>@foreach ($stages as $s)<th>{{ $stageLabels[$s] }}</th>@endforeach</tr></thead>
                    <tbody>
                        <tr>
                            @foreach ($stages as $i => $s)
                                @if ($currentIdx !== false && $i < $currentIdx)
                                    <td><i class="fa-solid fa-circle-check" style="color:#1B7A3D;"></i></td>
                                @elseif ($i === $currentIdx)
                                    <td>{!! view('components.status-badge', ['status' => $s])->render() !!}</td>
                                @else
                                    <td><i class="fa-regular fa-circle" style="color:var(--gray-border);"></i></td>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div style="margin-top:1.5rem;display:flex;gap:0.7rem;flex-wrap:wrap;">
        @php $s = $application->status; @endphp
        @if ($s === 'pending' || $s === 'screening' || $s === 'interview' || $s === 'offer')
            @if ($s === 'pending')
                <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="screening">
                    <button class="btn btn-success"><i class="fa-solid fa-check"></i> Shortlist</button>
                </form>
            @endif
            @if ($s === 'screening')
                <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="interview">
                    <button class="btn btn-outline"><i class="fa-solid fa-calendar"></i> Schedule Interview</button>
                </form>
            @endif
            @if ($s === 'interview')
                <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="offer">
                    <button class="btn btn-success"><i class="fa-solid fa-file-signature"></i> Extend Offer</button>
                </form>
            @endif
            @if ($s === 'offer')
                <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="hired">
                    <button class="btn btn-success"><i class="fa-solid fa-handshake"></i> Mark as Hired</button>
                </form>
            @endif
            <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;" onsubmit="return confirm('Reject this candidate?')">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
            </form>
        @endif
        @if ($s === 'rejected')
            <form action="{{ route('applications.status', $application) }}" method="POST" style="display:inline;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="{{ $application->statusHistories->first()?->previous_status ?? 'pending' }}">
                <button class="btn btn-success"><i class="fa-solid fa-rotate-left"></i> Revoke Rejection</button>
            </form>
        @endif
        <a href="{{ route('applications.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Applications</a>
    </div>

    @push('scripts')
    <style>
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem 1.5rem; margin-top: 0.8rem; }
        .detail-label { font-size: 0.75rem; color: var(--gray-text); font-weight: 500; }
        .detail-value { font-size: 0.88rem; color: var(--navy-deep); font-weight: 500; }
        .section-card { margin-bottom: 1rem; }
        .section-card h3 { display: flex; align-items: center; gap: 0.5rem; font-size: 1rem; font-weight: 700; color: var(--navy-deep); }
        .section-card h3 i { color: var(--blue-corporate); width: 18px; }
        .note-item { padding: 0.7rem 0; border-bottom: 1px solid var(--gray-light); }
        .note-item:last-child { border-bottom: none; }
        .note-meta { font-size: 0.75rem; color: var(--gray-text); margin-top: 0.2rem; }
        .doc-chip { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.7rem; background: var(--gray-bg); border-radius: 50px; font-size: 0.78rem; border: 1px solid var(--gray-border); }
        .empty-state { color: var(--gray-text); font-size: 0.82rem; padding: 1rem 0; text-align: center; }
        .consent-list { list-style: none; padding: 0; }
        .consent-list li { display: flex; align-items: center; gap: 0.5rem; padding: 0.3rem 0; font-size: 0.85rem; }
        .consent-list li i { width: 16px; }
    </style>
    @endpush
</x-app-layout>