<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicApplicationStoreRequest;
use App\Models\JobPosting;
use App\Services\ApplicationSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicApplicationController extends Controller
{
    public function create(JobPosting $jobPosting): View
    {
        if ($jobPosting->status !== 'published') {
            abort(404);
        }

        if ($jobPosting->deadline && $jobPosting->deadline->isBefore(now()->startOfDay())) {
            abort(410, 'This job posting has closed.');
        }

        $countries = DB::table('countries')->orderBy('name')->get(['id', 'name', 'phone_code']);

        return view('apply.show', compact('jobPosting', 'countries'));
    }

    public function store(PublicApplicationStoreRequest $request, JobPosting $jobPosting, ApplicationSubmissionService $service): RedirectResponse
    {
        if ($jobPosting->status !== 'published') {
            abort(404);
        }

        if ($jobPosting->deadline && $jobPosting->deadline->isBefore(now()->startOfDay())) {
            return back()->with('error', 'This job posting has closed.');
        }

        $data = $request->validated();

        $data['files'] = [
            'photo' => $request->file('files.photo'),
            'resume' => $request->file('files.resume'),
        ];

        $service->submit($jobPosting, $data);

        return redirect()->route('apply.create', $jobPosting)
            ->with('success', 'Your application has been submitted successfully. We will review it and get back to you.');
    }
}
