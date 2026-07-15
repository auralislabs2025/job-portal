<x-app-layout>
    <x-slot name="header">
        <div class="page-header flex-between">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Jobs</span></div>
                <h2>Job Listings</h2>
                <p>Manage all job postings across group companies.</p>
            </div>
            @can('post_jobs')
                <a href="{{ route('jobs.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create Job</a>
            @endcan
        </div>
    </x-slot>

    <div class="status-filters" id="statusFilters">
        <button class="status-filter-btn active" data-status="">All</button>
        <button class="status-filter-btn" data-status="draft">Draft</button>
        <button class="status-filter-btn" data-status="pending">Pending</button>
        <button class="status-filter-btn" data-status="published">Published</button>
    </div>

    <div class="filters-bar">
        <input type="text" id="jobSearch" placeholder="Search by job title, company or location...">
        <select id="companyFilter">
            <option value="">All Group Companies</option>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="card">
        <div class="table-container">
            <table id="jobsTable">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Title</th>
                        <th>Group Company</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Applications</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        let activeStatus = '';

        const table = $('#jobsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('jobs.data') }}',
                data: function (d) {
                    d.status_filter = activeStatus;
                    d.company_filter = $('#companyFilter').val();
                }
            },
            columns: [
                { data: 'code', name: 'code' },
                { data: 'title', name: 'title' },
                { data: 'group_company_id', name: 'group_company_id', searchable: false },
                { data: 'employment_type', name: 'employment_type', searchable: false },
                { data: 'location', name: 'location' },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'applications_count', name: 'applications_count', searchable: false },
                { data: 'deadline', name: 'deadline', searchable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false },
            ],
            pageLength: 15,
            order: [[0, 'desc']],
            language: { search: '', searchPlaceholder: 'Search...' },
            dom: '<"top"lf>rt<"bottom"ip>',
        });

        document.querySelectorAll('.status-filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.status-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                activeStatus = this.dataset.status;
                table.ajax.reload();
            });
        });

        document.getElementById('companyFilter').addEventListener('change', function () {
            table.ajax.reload();
        });

        document.getElementById('jobSearch').addEventListener('keyup', function () {
            table.search(this.value).draw();
        });

        $(document).on('submit', '.ajax-job-action', function (e) {
            e.preventDefault();
            const form = $(this);
            const btn = form.find('button[type="submit"]');
            btn.prop('disabled', true).text('Processing...');
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function () {
                    table.ajax.reload(null, false);
                },
                error: function () {
                    table.ajax.reload(null, false);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>