<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Applications</span></div>
                <h2>Applications</h2>
                <p>Track all candidate applications across group companies.</p>
            </div>
        </div>
    </x-slot>

    <div class="filters-bar">
        <input type="text" id="appSearch" placeholder="Search by candidate, email, mobile or job title...">
        <select id="statusFilter">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="screening">Screening</option>
            <option value="interview">Interview</option>
            <option value="offer">Offer</option>
            <option value="hired">Hired</option>
            <option value="rejected">Rejected</option>
        </select>
        <select id="agencyFilter">
            <option value="">All Agencies</option>
            @foreach ($agencies as $agency)
                <option value="{{ $agency->id }}">{{ $agency->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="card">
        <div class="table-container">
            <table id="applicationsTable">
                <thead>
                    <tr>
                        <th>App ID</th>
                        <th>Candidate</th>
                        <th>Mobile</th>
                        <th>Job Title</th>
                        <th>Group Company</th>
                        <th>Status</th>
                        <th>Date</th>
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
        const table = $('#applicationsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('applications.data') }}',
                data: function (d) {
                    d.status_filter = $('#statusFilter').val();
                    d.agency_filter = $('#agencyFilter').val();
                }
            },
            columns: [
                { data: 'code', name: 'code' },
                { data: 'candidate_display_name', name: 'candidate_display_name' },
                { data: 'mobile', name: 'mobile', searchable: false },
                { data: 'job_posting_id', name: 'job_posting_id', searchable: false },
                { data: 'group_company', name: 'group_company', searchable: false, orderable: false },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'submitted_at', name: 'created_at', searchable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false },
            ],
            pageLength: 15,
            order: [[6, 'desc']],
            language: { search: '', searchPlaceholder: 'Search...' },
            dom: '<"top"lf>rt<"bottom"ip>',
        });

        document.getElementById('statusFilter').addEventListener('change', () => table.ajax.reload());
        document.getElementById('agencyFilter').addEventListener('change', () => table.ajax.reload());
        document.getElementById('appSearch').addEventListener('keyup', function () {
            table.search(this.value).draw();
        });
    </script>
    @endpush
</x-app-layout>