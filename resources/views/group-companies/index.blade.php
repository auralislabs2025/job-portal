<x-app-layout>
    <x-slot name="header">
        <div class="page-header flex-between">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Group Companies</span></div>
                <h2>Group Companies</h2>
                <p>Manage all divisions and subsidiaries of ABN Corporation.</p>
            </div>
            @can('manage_companies')
                <button class="btn btn-primary" onclick="openModal('companyModal')">
                    <i class="fa-solid fa-plus"></i> Add New Group Company
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="company-grid" id="companiesGrid">
        @forelse ($companies as $company)
            <div class="card company-card">
                <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:0.8rem;">
                    <div class="stat-icon blue" style="font-size:1.5rem;overflow:hidden;">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            🏢
                        @endif
                    </div>
                    <div>
                        <h3 style="font-size:1rem;font-weight:700;color:var(--navy-deep);">{{ $company->name }}</h3>
                        @if($company->code)
                            <p style="font-size:0.78rem;color:var(--gray-text);">Code: {{ $company->code }}</p>
                        @endif
                    </div>
                </div>
                <div style="font-size:0.8rem;color:var(--gray-dark);">
                    @if($company->email)<p><i class="fa-solid fa-envelope"></i> {{ $company->email }}</p>@endif
                    @if($company->phone)<p><i class="fa-solid fa-phone"></i> {{ $company->phone }}</p>@endif
                    @if($company->city || $company->country)<p><i class="fa-solid fa-location-dot"></i> {{ $company->city }}{{ $company->city && $company->country ? ', ' : '' }}{{ $company->country }}</p>@endif
                    <p><i class="fa-solid fa-briefcase"></i> {{ $company->open_jobs ?? 0 }} open jobs</p>
                    <p><i class="fa-solid fa-clock"></i> {{ $company->pending_approval ?? 0 }} pending approval</p>
                    <p><x-status-badge :status="$company->is_active ? 'active' : 'inactive'" /></p>
                </div>
                @can('manage_companies')
                    <div class="flex-between mt-1">
                        <span class="text-muted">Created {{ $company->created_at->format('M d, Y') }}</span>
                        <div>
                            <button class="btn btn-sm btn-outline" onclick="editCompany({{ $company->id }})"><i class="fa-solid fa-pen"></i></button>
                            <form method="POST" action="{{ route('group-companies.destroy', $company) }}" style="display:inline" onsubmit="return confirm('Delete {{ $company->name }}? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        @empty
            <x-empty-state icon="fa-building" message="No group companies added yet." actionLabel="Add your first company" actionUrl="#" onclick="openModal('companyModal')" />
        @endforelse
    </div>

    {{-- Add/Edit Modal --}}
    <x-modal-wrapper name="companyModal" title="Add New Group Company">
        <form method="POST" action="{{ route('group-companies.store') }}" id="companyForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="companyFormMethod" value="POST">
            <div class="form-group">
                <label>Company Name *</label>
                <input type="text" name="name" id="companyName" class="form-control" required placeholder="e.g. Transport Division">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" name="code" id="companyCode" class="form-control" placeholder="e.g. TRANS">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="companyEmail" class="form-control" placeholder="admin@company.com">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="companyPhone" class="form-control" placeholder="+971 4 123 4567">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" id="companyCity" class="form-control" placeholder="Dubai">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Country</label>
                    <select name="country" id="companyCountry" class="form-control">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" id="companyIsActive" value="1" checked>
                        Active
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" id="companyLogo" class="form-control" accept="image/*">
                <div id="logoPreview" style="margin-top:0.5rem;display:none;">
                    <img src="" alt="Logo preview" style="max-width:100px;max-height:60px;border-radius:6px;border:1px solid var(--gray-border);">
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" id="companyAddress" class="form-control" placeholder="Street address">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="companyDescription" class="form-control" rows="3" placeholder="Brief overview..."></textarea>
            </div>
            <div class="flex-between">
                <button type="button" class="btn btn-outline" onclick="closeModal('companyModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="companySubmitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save Company
                </button>
            </div>
        </form>
    </x-modal-wrapper>

    @push('scripts')
    <script>
        const companies = @json($companiesJson);

        document.getElementById('companyLogo')?.addEventListener('change', function() {
            const preview = document.getElementById('logoPreview');
            const img = preview.querySelector('img');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { img.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(this.files[0]);
            } else {
                preview.style.display = 'none';
            }
        });

        window.editCompany = function(id) {
            const company = companies.find(c => c.id === id);
            if (!company) return;

            document.getElementById('companyForm').action = '{{ url('group-companies') }}/' + id;
            document.getElementById('companyFormMethod').value = 'PUT';
            document.querySelector('#companyModal .modal-header h3').textContent = 'Edit Group Company';
            document.getElementById('companySubmitBtn').innerHTML = '<i class="fa-solid fa-save"></i> Update Company';

            document.getElementById('companyName').value = company.name || '';
            document.getElementById('companyCode').value = company.code || '';
            document.getElementById('companyEmail').value = company.email || '';
            document.getElementById('companyPhone').value = company.phone || '';
            document.getElementById('companyCity').value = company.city || '';
            document.getElementById('companyCountry').value = company.country || '';
            document.getElementById('companyAddress').value = company.address || '';
            document.getElementById('companyDescription').value = company.description || '';
            document.getElementById('companyIsActive').checked = company.is_active;

            const logoPreview = document.getElementById('logoPreview');
            const logoImg = logoPreview.querySelector('img');
            if (company.logo) {
                logoImg.src = '/storage/' + company.logo;
                logoPreview.style.display = 'block';
            } else {
                logoPreview.style.display = 'none';
            }

            openModal('companyModal');
        };

        document.getElementById('companyModalOverlay')?.addEventListener('click', (e) => {
            if (e.target.id === 'companyModalOverlay') closeModal('companyModal');
        });

        // Reset modal on open for new
        document.querySelector('[onclick*="openModal(\'companyModal\'"]')?.addEventListener('click', function() {
            document.getElementById('companyForm').action = '{{ route('group-companies.store') }}';
            document.getElementById('companyFormMethod').value = 'POST';
            document.querySelector('#companyModal .modal-header h3').textContent = 'Add New Group Company';
            document.getElementById('companySubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Company';
            document.getElementById('companyForm').reset();
            document.getElementById('companyIsActive').checked = true;
            document.getElementById('companyLogo').value = '';
            document.getElementById('logoPreview').style.display = 'none';
        });
    </script>
    @endpush
</x-app-layout>
