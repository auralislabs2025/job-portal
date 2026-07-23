<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <a href="{{ route('jobs.index') }}">Jobs</a> <span>/</span> <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a> <span>/</span> <span>Edit</span></div>
                <h2>Edit Job: {{ $job->title }}</h2>
                <p>{{ $job->groupCompany?->name ?? '—' }} — {{ $job->code ?? '#' . $job->id }}</p>
            </div>
        </div>
    </x-slot>

    <div class="card" style="max-width:800px;">
        <form method="POST" action="{{ route('jobs.update', $job) }}" id="editJobForm">
            @csrf
            @method('PUT')
            <div class="grid-2">
                <div class="form-group">
                    <label>Job Title *</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. Senior Fleet Manager" value="{{ old('title', $job->title) }}">
                    @error('title')<span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Group Company *</label>
                    <select name="group_company_id" class="form-control" required>
                        <option value="">Select Group Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('group_company_id', $job->group_company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                    @error('group_company_id')<span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Employment Type *</label>
                    <select name="employment_type" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="full_time" {{ old('employment_type', $job->employment_type) == 'full_time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part_time" {{ old('employment_type', $job->employment_type) == 'part_time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                    @error('employment_type')<span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Location *</label>
                    <input type="text" name="location" class="form-control" required placeholder="e.g. Dubai, UAE" value="{{ old('location', $job->location) }}">
                    @error('location')<span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Salary Range</label>
                    <input type="text" name="salary" class="form-control" placeholder="e.g. AED 15,000 - 25,000" value="{{ old('salary', $job->salary) }}">
                </div>
                <div class="form-group">
                    <label>Application Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $job->deadline?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="form-group">
                <label>Notification Groups</label>
                <div class="multi-select" id="notificationMultiSelect">
                    <div class="multi-select-trigger" onclick="toggleMultiSelect()">
                        <span style="color:var(--gray-text);">Select notification groups...</span>
                    </div>
                    <div class="multi-select-dropdown" id="multiSelectDropdown">
                        @foreach ($notificationGroups as $ng)
                            <label>
                                <input type="checkbox" name="notification_group_ids[]" value="{{ $ng->id }}" data-label="{{ $ng->name }} ({{ $ng->groupCompany?->name ?? 'All' }})" {{ in_array($ng->id, old('notification_group_ids', $job->notificationGroups->pluck('id')->toArray())) ? 'checked' : '' }} onchange="updateMultiSelect()">
                                {{ $ng->name }} ({{ $ng->groupCompany?->name ?? 'All' }})
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Job Description *</label>
                <textarea name="description" class="form-control" rows="14" required placeholder="Describe the role, responsibilities, qualifications, and requirements...">{{ old('description', $job->description) }}</textarea>
                @error('description')<span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>@enderror
            </div>

            <div class="flex-between" style="gap:0.7rem;">
                <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Cancel</a>
                <div style="display:flex;gap:0.7rem;">
                    <button type="submit" name="action" value="draft" class="btn btn-outline"><i class="fa-solid fa-save"></i> Save Draft</button>
                    @if ($job->status === 'draft')
                        <button type="submit" name="action" value="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit for Approval</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function toggleMultiSelect() {
            document.getElementById('multiSelectDropdown').classList.toggle('show');
        }
        function updateMultiSelect() {
            const checks = document.querySelectorAll('#multiSelectDropdown input:checked');
            const trigger = document.querySelector('.multi-select-trigger');
            if (checks.length === 0) {
                trigger.innerHTML = '<span style="color:var(--gray-text);">Select notification groups...</span>';
            } else {
                trigger.innerHTML = Array.from(checks).map(c => {
                    const label = c.dataset.label;
                    return `<span class="chip" data-value="${c.value}">${label} <i class="fa-solid fa-xmark" style="font-size:0.6rem;cursor:pointer;"></i></span>`;
                }).join('');
            }
        }
        document.addEventListener('click', (e) => {
            const target = e.target;
            if (target.closest('.chip') && target.classList.contains('fa-xmark')) {
                const chip = target.closest('.chip');
                const val = chip.dataset.value;
                chip.remove();
                document.querySelector(`#multiSelectDropdown input[value="${val}"]`).checked = false;
                updateMultiSelect();
                e.stopPropagation();
                return;
            }
            if (!target.closest('.multi-select')) {
                document.getElementById('multiSelectDropdown')?.classList.remove('show');
            }
        });
        document.addEventListener('DOMContentLoaded', updateMultiSelect);
    </script>
    @endpush
</x-app-layout>