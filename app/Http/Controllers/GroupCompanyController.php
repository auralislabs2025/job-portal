<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupCompanyStoreRequest;
use App\Http\Requests\GroupCompanyUpdateRequest;
use App\Models\GroupCompany;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupCompanyController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('manage_companies'), 403);
        $companies = GroupCompany::withCount([
            'jobPostings as open_jobs' => fn ($q) => $q->where('status', 'published'),
            'jobPostings as pending_approval' => fn ($q) => $q->where('status', 'pending'),
        ])->latest()->get();

        $companiesJson = $companies->map(fn ($c) => [
            'id' => $c->id, 'name' => $c->name, 'code' => $c->code,
            'email' => $c->email, 'phone' => $c->phone, 'city' => $c->city,
            'country' => $c->country, 'address' => $c->address,
            'description' => $c->description, 'is_active' => $c->is_active,
        ]);

        return view('group-companies.index', compact('companies', 'companiesJson'));
    }

    public function store(GroupCompanyStoreRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_companies'), 403);
        $company = GroupCompany::create($request->validated());

        ActivityLogger::log(
            action: 'created',
            module: 'group_companies',
            recordId: $company->id,
            newValues: $request->validated(),
        );

        return redirect()->route('group-companies.index')
            ->with('success', 'Group company created successfully.');
    }

    public function update(GroupCompanyUpdateRequest $request, GroupCompany $groupCompany): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_companies'), 403);
        $old = $groupCompany->toArray();
        $groupCompany->update($request->validated());

        ActivityLogger::log(
            action: 'updated',
            module: 'group_companies',
            recordId: $groupCompany->id,
            oldValues: $old,
            newValues: $request->validated(),
        );

        return redirect()->route('group-companies.index')
            ->with('success', 'Group company updated successfully.');
    }

    public function destroy(GroupCompany $groupCompany): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_companies'), 403);
        $groupCompany->delete();

        ActivityLogger::log(
            action: 'deleted',
            module: 'group_companies',
            recordId: $groupCompany->id,
            oldValues: $groupCompany->toArray(),
        );

        return redirect()->route('group-companies.index')
            ->with('success', 'Group company deleted successfully.');
    }
}
