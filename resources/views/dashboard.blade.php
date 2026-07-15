<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Dashboard</span></div>
                <h2>Dashboard Overview</h2>
                <p>Welcome back, {{ auth()->user()->name }}. Here's your recruitment summary for ABN Corporation.</p>
            </div>
        </div>
    </x-slot>

    <div class="stats-grid">
        <x-stat-card icon="fa-briefcase" variant="blue" :value="$stats['open_jobs']" label="Open Jobs" />
        <x-stat-card icon="fa-clock" variant="gold" :value="$stats['pending_approval']" label="Pending Approval" />
        <x-stat-card icon="fa-file-lines" variant="green" :value="$stats['applications']" label="Applications" />
        <x-stat-card icon="fa-user-check" variant="blue" :value="$stats['hired']" label="Hired" />
        <x-stat-card icon="fa-file-pen" variant="gold" :value="$stats['published_jobs']" label="Published Jobs" />
        <x-stat-card icon="fa-calendar-check" variant="green" :value="$stats['interviews']" label="Interviews" />
        <x-stat-card icon="fa-file-signature" variant="blue" :value="$stats['offers']" label="Offers" />
        <x-stat-card icon="fa-ban" variant="red" :value="$stats['rejected']" label="Rejected" />
    </div>

    <div class="grid-2">
        <div class="card mb-2">
            <div class="card-header"><h3>Applications Trend</h3></div>
            <canvas id="applicationsChart" height="100"></canvas>
        </div>
        <div class="card mb-2">
            <div class="card-header"><h3>Hiring Funnel</h3></div>
            <canvas id="funnelChart" height="100"></canvas>
        </div>
    </div>

    <div class="grid-2">
        <div class="card">
            <div class="card-header"><h3>Recent Activity</h3></div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentActivity as $log)
                            <tr>
                                <td>{{ $log['action'] }}</td>
                                <td>{{ $log['user'] }}</td>
                                <td>{{ $log['timestamp'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center">No recent activity.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>Recent Applications</h3></div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentApplications as $app)
                            <tr>
                                <td><a href="{{ route('applications.show', $app['id']) }}" style="color:var(--blue-corporate);font-weight:600;">{{ $app['name'] }}</a></td>
                                <td>{{ $app['job_title'] }}</td>
                                <td>{!! view('components.status-badge', ['status' => $app['status']])->render() !!}</td>
                                <td>{{ $app['submitted_at'] }}</td>
                                <td>
                                    @php $resume = collect($app['documents'])->firstWhere('type', 'resume'); @endphp
                                    @if ($resume)
                                        <a href="{{ route('documents.download', $resume['id']) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-download"></i> Resume</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted text-center">No applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trendData = @json($applicationsTrend);
            const funnelData = @json($funnelData);

            const ctx1 = document.getElementById('applicationsChart');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: trendData.labels,
                        datasets: [{
                            label: 'Applications',
                            data: trendData.data,
                            borderColor: '#1A3A5C',
                            backgroundColor: 'rgba(26,58,92,0.08)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#C8A94E',
                            pointBorderColor: '#C8A94E',
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: '#E8EBF0' } }, x: { grid: { display: false } } }
                    }
                });
            }

            const ctx2 = document.getElementById('funnelChart');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: funnelData.labels,
                        datasets: [{
                            label: 'Candidates',
                            data: funnelData.data,
                            backgroundColor: ['#E2E8F0', '#93C5FD', '#FCD34D', '#86EFAC', '#16A34A'],
                            borderRadius: 4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: '#E8EBF0' } }, x: { grid: { display: false } } }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
