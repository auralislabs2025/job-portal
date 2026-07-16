<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupCompanyStoreRequest;
use App\Http\Requests\GroupCompanyUpdateRequest;
use App\Models\GroupCompany;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $countries = DB::table('countries')->orderBy('name')->get(['id', 'name']);

        $companiesJson = $companies->map(fn ($c) => [
            'id' => $c->id, 'name' => $c->name, 'code' => $c->code,
            'email' => $c->email, 'phone' => $c->phone, 'city' => $c->city,
            'country' => $c->country, 'address' => $c->address,
            'description' => $c->description, 'logo' => $c->logo,
            'is_active' => $c->is_active,
        ]);

        return view('group-companies.index', compact('companies', 'countries', 'companiesJson'));
    }

    public function store(GroupCompanyStoreRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_companies'), 403);
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $company = GroupCompany::create($data);

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
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            if ($groupCompany->logo) {
                Storage::disk('public')->delete($groupCompany->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }
        $groupCompany->update($data);

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
