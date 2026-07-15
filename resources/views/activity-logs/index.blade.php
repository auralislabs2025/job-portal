<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Activity Logs</span></div>
            <h2>Activity Logs</h2>
            <p>Track all recruitment activities across the organization.</p>
        </div>
    </x-slot>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Details</th>
                        <th>User</th>
                        <th>IP Address</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td><span class="badge badge-{{ $log->action === 'created' ? 'published' : ($log->action === 'deleted' ? 'rejected' : 'pending') }}">{{ ucfirst($log->action) }}</span></td>
                            <td>{{ str_replace('_', ' ', ucfirst($log->module)) }}</td>
                            <td style="max-width:300px;font-size:0.8rem;">
                                @if($log->action === 'created')
                                    Created record #{{ $log->record_id }}
                                @elseif($log->action === 'updated')
                                    Updated record #{{ $log->record_id }}
                                    @if($log->new_values)
                                        <span class="text-muted">({{ implode(', ', array_keys($log->new_values)) }})</span>
                                    @endif
                                @elseif($log->action === 'deleted')
                                    Deleted record #{{ $log->record_id }}
                                @else
                                    {{ $log->action }} record #{{ $log->record_id }}
                                @endif
                            </td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td style="font-size:0.78rem;font-family:monospace;">{{ $log->ip_address ?? '—' }}</td>
                            <td style="white-space:nowrap;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem;">No activity logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
