<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\GroupCompany;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\DataTables;

class ApplicationController extends Controller
{
    public function downloadDocument(ApplicationDocument $document): StreamedResponse|RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('view_applications'), 403);

        if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('view_applications'), 403);
        $agencies = GroupCompany::forUser(auth()->user())->where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('applications.index', compact('agencies'));
    }

    public function data(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('view_applications'), 403);
        $user = auth()->user();

        $query = Application::query()
            ->with(['jobPosting.groupCompany'])
            ->join('job_postings', 'applications.job_posting_id', '=', 'job_postings.id')
            ->select('applications.*');

        if ($user->group_company_id !== null) {
            $query->where('job_postings.group_company_id', $user->group_company_id);
        }

        return DataTables::of($query)
            ->addColumn('status', fn (Application $app) => view('components.status-badge', ['status' => $app->status ?? 'pending'])->render())
            ->addColumn('actions', fn (Application $app) => '<a href="' . route('applications.show', $app) . '" class="btn btn-sm btn-outline">View Profile</a>')
            ->editColumn('code', fn (Application $app) => $app->code ?? '#' . $app->id)
            ->editColumn('candidate_display_name', fn (Application $app) => '<a href="' . route('applications.show', $app) . '" style="color:var(--blue-corporate);font-weight:600;">' . e($app->candidate_display_name ?? 'Candidate #' . $app->id) . '</a>')
            ->editColumn('job_posting_id', fn (Application $app) => e($app->jobPosting?->title ?? '—'))
            ->addColumn('group_company', fn (Application $app) => e($app->jobPosting?->groupCompany?->name ?? '—'))
            ->editColumn('submitted_at', fn (Application $app) => $app->submitted_at ? $app->submitted_at->format('M d, Y') : '—')
            ->editColumn('mobile', fn (Application $app) => e($app->mobile ?? '—'))
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('applications.candidate_display_name', 'ilike', "%{$search}%")
                          ->orWhere('applications.email', 'ilike', "%{$search}%")
                          ->orWhere('applications.mobile', 'ilike', "%{$search}%")
                          ->orWhere('applications.code', 'ilike', "%{$search}%")
                          ->orWhere('job_postings.title', 'ilike', "%{$search}%");
                    });
                }
                if ($status = $request->input('status_filter')) {
                    $query->where('applications.status', $status);
                }
                if ($agencyId = $request->input('agency_filter')) {
                    $query->where('job_postings.group_company_id', $agencyId);
                }
            })
            ->order(function ($query) {
                $query->orderBy('applications.created_at', 'desc');
            })
            ->rawColumns(['status', 'actions', 'candidate_display_name'])
            ->make(true);
    }

    public function show(Application $application): View
    {
        abort_unless(auth()->user()->hasPermission('view_applications'), 403);
        $application->load([
            'jobPosting.groupCompany',
            'assignedTo',
            'reviewedBy',
            'personalDetails',
            'jobDetails',
            'educations',
            'employment',
            'previousEmployers',
            'passportVisa',
            'drivingLicense',
            'documents',
            'additionalInfo',
            'statusHistories' => fn ($q) => $q->with('changedByUser')->latest('changed_at'),
            'notes' => fn ($q) => $q->with('user')->latest(),
        ]);

        return view('applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_candidates'), 403);
        $validated = $request->validate([
            'status' => 'required|string|in:pending,screening,interview,offer,hired,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $old = $application->only('status');
        $application->update(['status' => $validated['status']]);

        ActivityLogger::log(
            action: 'status_updated',
            module: 'applications',
            recordId: $application->id,
            oldValues: $old,
            newValues: ['status' => $validated['status']],
        );

        return redirect()->route('applications.show', $application)
            ->with('success', 'Application status updated.');
    }
}
