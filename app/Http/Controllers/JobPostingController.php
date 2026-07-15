<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobPostingStoreRequest;
use App\Http\Requests\JobPostingUpdateRequest;
use App\Http\Requests\JobStatusRequest;
use App\Models\GroupCompany;
use App\Models\JobPosting;
use App\Models\NotificationGroup;
use App\Services\ActivityLogger;
use App\Services\JobStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class JobPostingController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('post_jobs'), 403);
        $companies = GroupCompany::forUser(auth()->user())->where('is_active', true)->orderBy('name')->get();

        return view('jobs.index', compact('companies'));
    }

    public function data(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('post_jobs'), 403);
        $user = auth()->user();

        $appCounts = DB::raw('(SELECT job_posting_id, COUNT(*) as total FROM applications GROUP BY job_posting_id) as app_counts');

        $query = JobPosting::query()
            ->with(['groupCompany'])
            ->leftJoin($appCounts, 'job_postings.id', '=', 'app_counts.job_posting_id')
            ->select('job_postings.*', DB::raw('COALESCE(app_counts.total, 0) as applications_count'));

        if ($user->hasPermission('approve_jobs')) {
            if ($user->group_company_id !== null) {
                $query->where('job_postings.group_company_id', $user->group_company_id);
            }
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('job_postings.created_by', $user->id)
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('job_postings.group_company_id', $user->group_company_id)
                          ->where('job_postings.status', '!=', 'draft');
                  });
            });
        }

        return DataTables::of($query)
            ->addColumn('status', fn (JobPosting $job) => view('components.status-badge', ['status' => $job->status])->render())
            ->addColumn('actions', fn (JobPosting $job) => view('jobs.partials.actions', compact('job'))->render())
            ->editColumn('title', fn (JobPosting $job) => '<a href="' . route('jobs.show', $job) . '" style="color:var(--blue-corporate);font-weight:600;">' . e($job->title) . '</a>')
            ->editColumn('employment_type', fn (JobPosting $job) => $job->employment_type ? str_replace('_', ' ', ucfirst($job->employment_type)) : '—')
            ->editColumn('group_company_id', fn (JobPosting $job) => $job->groupCompany?->name ?? '—')
            ->editColumn('deadline', fn (JobPosting $job) => $job->deadline ? $job->deadline->format('M d, Y') : '—')
            ->editColumn('code', fn (JobPosting $job) => $job->code ?? '#' . $job->id)
            ->editColumn('applications_count', fn (JobPosting $job) => (string) ($job->applications_count ?? 0))
            ->filterColumn('group_company_id', function ($query, $keyword) {
                $query->whereHas('groupCompany', fn ($q) => $q->where('name', 'like', "%{$keyword}%"));
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('job_postings.title', 'ilike', "%{$search}%")
                          ->orWhere('job_postings.location', 'ilike', "%{$search}%")
                          ->orWhere('job_postings.code', 'ilike', "%{$search}%");
                    });
                }
                if ($status = $request->input('status_filter')) {
                    $query->where('job_postings.status', $status);
                }
                if ($companyId = $request->input('company_filter')) {
                    $query->where('job_postings.group_company_id', $companyId);
                }
            })
            ->rawColumns(['status', 'title', 'actions'])
            ->make(true);
    }

    public function create(): View
    {
        abort_unless(auth()->user()->hasPermission('post_jobs'), 403);
        $companies = GroupCompany::where('is_active', true)->get();
        $notificationGroups = NotificationGroup::with('groupCompany')->get();

        return view('jobs.create', compact('companies', 'notificationGroups'));
    }

    public function store(JobPostingStoreRequest $request, JobStatusService $service): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('post_jobs'), 403);
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';

        $job = JobPosting::create($data);

        if ($notificationIds = $request->input('notification_group_ids')) {
            $job->notificationGroups()->sync($notificationIds);
        }

        if ($request->input('action') === 'submit') {
            $service->transition($job, 'submit');
        }

        ActivityLogger::log(
            action: 'created',
            module: 'job_postings',
            recordId: $job->id,
            newValues: $data,
        );

        return redirect()->route('jobs.index')
            ->with('success', 'Job created successfully.');
    }

    public function show(JobPosting $job): View
    {
        abort_unless(auth()->user()->hasPermission('post_jobs'), 403);
        $job->load([
            'groupCompany',
            'createdBy',
            'approvedBy',
            'notificationGroups',
            'jobStatusHistories' => fn ($q) => $q->with('changedByUser')->latest('changed_at'),
        ]);

        $job->applications_count = $job->applications()->count();

        return view('jobs.show', compact('job'));
    }

    public function edit(JobPosting $job): View
    {
        $user = auth()->user();

        $canEdit = $user->hasPermission('approve_jobs')
            || ($user->hasPermission('post_jobs') && $job->created_by === $user->id && $job->status === 'draft');

        abort_unless($canEdit, 403);

        $companies = GroupCompany::where('is_active', true)->get();
        $notificationGroups = NotificationGroup::with('groupCompany')->get();

        return view('jobs.edit', compact('job', 'companies', 'notificationGroups'));
    }

    public function update(JobPostingUpdateRequest $request, JobPosting $job, JobStatusService $service): RedirectResponse
    {
        $old = $job->toArray();
        $job->update($request->validated());

        if ($request->has('notification_group_ids')) {
            $job->notificationGroups()->sync($request->input('notification_group_ids'));
        }

        if ($request->input('action') === 'submit') {
            $service->transition($job, 'submit');
        }

        ActivityLogger::log(
            action: 'updated',
            module: 'job_postings',
            recordId: $job->id,
            oldValues: $old,
            newValues: $job->toArray(),
        );

        return redirect()->route('jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(JobPosting $job): RedirectResponse
    {
        $user = auth()->user();
        $canDelete = $user->hasPermission('approve_jobs')
            || ($user->hasPermission('post_jobs') && $job->created_by === $user->id && $job->status === 'draft');
        abort_unless($canDelete, 403);
        $job->delete();

        ActivityLogger::log(
            action: 'deleted',
            module: 'job_postings',
            recordId: $job->id,
        );

        return redirect()->route('jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function updateStatus(JobStatusRequest $request, JobPosting $jobPosting, JobStatusService $service): RedirectResponse|JsonResponse
    {
        $service->transition(
            $jobPosting,
            $request->input('action'),
            $request->input('rejected_reason'),
        );

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('jobs.show', $jobPosting)
            ->with('success', 'Job status updated.');
    }
}
