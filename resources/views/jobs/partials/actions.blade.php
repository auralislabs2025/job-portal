<a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline">View</a>
@can('approve_jobs')
    <a href="{{ route('jobs.edit', $job) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i> Edit</a>
@endcan
@can('post_jobs')
    @if ($job->status === 'draft' && $job->created_by === auth()->id())
        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i> Edit</a>
    @endif
    @if ($job->status === 'draft')
        <form class="ajax-job-action" method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="submit">
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </form>
    @endif
@endcan
@can('approve_jobs')
    @if ($job->status === 'draft' || $job->status === 'pending')
        <form class="ajax-job-action" method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="approve">
            <button type="submit" class="btn btn-sm btn-success">Approve &amp; Publish</button>
        </form>
    @endif
    @if ($job->status === 'published')
        <form class="ajax-job-action" method="POST" action="{{ route('jobs.status', $job) }}" style="display:inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="action" value="revert">
            <button type="submit" class="btn btn-sm btn-warning">Revert to Pending</button>
        </form>
    @endif
@endcan
