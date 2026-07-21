<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <a href="{{ route('jobs.index') }}">Jobs</a> <span>/</span> <span>{{ $job->title }}</span></div>
                <h2>{{ $job->title }}</h2>
                <p>{{ $job->groupCompany?->name ?? '—' }} — {{ $job->location ?? '—' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid-2">
        <div class="card">
            <h3>Job Information</h3>
            <table style="margin-top:1rem;">
                <tr><td style="font-weight:600;width:140px;">Job ID</td><td>{{ $job->code ?? '#' . $job->id }}</td></tr>
                <tr><td style="font-weight:600;">Status</td><td><x-status-badge :status="$job->status" /></td></tr>
                <tr><td style="font-weight:600;">Type</td><td>{{ $job->employment_type ? str_replace('_', ' ', ucfirst($job->employment_type)) : '—' }}</td></tr>
                <tr><td style="font-weight:600;">Location</td><td>{{ $job->location ?? '—' }}</td></tr>
                <tr><td style="font-weight:600;">Salary</td><td>{{ $job->salary ?? '—' }}</td></tr>
                <tr><td style="font-weight:600;">Deadline</td><td>{{ $job->deadline ? $job->deadline->format('M d, Y') : '—' }}</td></tr>
                <tr><td style="font-weight:600;">Applications</td><td>{{ $job->applications_count }}</td></tr>
                <tr><td style="font-weight:600;">Created By</td><td>{{ $job->createdBy?->name ?? '—' }}</td></tr>
                <tr><td style="font-weight:600;">Notification Groups</td><td>{{ $job->notificationGroups->pluck('name')->implode(', ') ?: 'None' }}</td></tr>
                @if ($job->approved_by)
                    <tr><td style="font-weight:600;">Approved By</td><td>{{ $job->approvedBy?->name }}</td></tr>
                    <tr><td style="font-weight:600;">Approved At</td><td>{{ $job->approved_at?->format('M d, Y H:i') }}</td></tr>
                @endif
                @if ($job->published_at)
                    <tr><td style="font-weight:600;">Published At</td><td>{{ $job->published_at->format('M d, Y H:i') }}</td></tr>
                @endif
                @if ($job->rejected_reason)
                    <tr><td style="font-weight:600;">Rejected Reason</td><td style="color:var(--danger);">{{ $job->rejected_reason }}</td></tr>
                @endif
            </table>
        </div>
        <div class="card">
            <h3>Description</h3>
            <p style="white-space:pre-wrap;margin-top:1rem;">{{ $job->description ?? 'No description provided.' }}</p>
        </div>
    </div>

    <div class="grid-2" style="margin-top:1.5rem;">
        <div class="card">
            <h3>Status History</h3>
            <div style="margin-top:1rem;">
                @forelse ($job->jobStatusHistories as $history)
                    <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.6rem 0;border-bottom:1px solid var(--gray-border);">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--blue-corporate);margin-top:0.3rem;flex-shrink:0;"></div>
                        <div>
                            <p style="font-weight:600;font-size:0.85rem;">
                                {{ ucfirst($history->previous_status ?? '—') }} → {{ ucfirst($history->current_status) }}
                            </p>
                            <p style="font-size:0.78rem;color:var(--gray-text);">
                                {{ $history->changedByUser?->name ?? 'System' }} · {{ $history->changed_at->format('M d, Y H:i') }}
                            </p>
                            @if ($history->remarks)
                                <p style="font-size:0.8rem;color:var(--danger);margin-top:0.2rem;">{{ $history->remarks }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No status history yet.</p>
                @endforelse
            </div>
        </div>
        <div class="card">
            <h3>Actions</h3>
            <div style="display:flex;flex-wrap:wrap;gap:0.6rem;margin-top:1rem;">
                <a href="{{ route('applications.index', ['job' => $job->id]) }}" class="btn btn-primary"><i class="fa-solid fa-file-lines"></i> View Applications</a>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Jobs</a>
                @can('approve_jobs')
                    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> Edit</a>
                @else
                    @can('post_jobs')
                        @if ($job->status === 'draft' && $job->created_by === auth()->id())
                            <a href="{{ route('jobs.edit', $job) }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> Edit</a>
                        @endif
                    @endcan
                @endcan

                @can('post_jobs')
                    @if ($job->status === 'draft')
                        <form method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="submit">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Submit for Approval</button>
                        </form>
                    @endif
                @endcan

                @can('approve_jobs')
                    @if ($job->status === 'draft' || $job->status === 'pending')
                        <form method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Approve &amp; Publish</button>
                        </form>
                        <form method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline" onsubmit="event.preventDefault(); showConfirm('Reject this job?', this)">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="reject">
                            <input type="text" name="rejected_reason" placeholder="Rejection reason" required style="padding:0.4rem;border:1px solid var(--gray-border);border-radius:4px;font-size:0.8rem;">
                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    @elseif ($job->status === 'published')
                        <form method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline" onsubmit="event.preventDefault(); showConfirm('Revert this job to pending?', this)">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="revert">
                            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-rotate-left"></i> Revert to Pending</button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
