<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationGroupStoreRequest;
use App\Http\Requests\NotificationGroupUpdateRequest;
use App\Models\GroupCompany;
use App\Models\NotificationGroup;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationGroupController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('manage_notification_groups'), 403);
        $groups = NotificationGroup::with(['groupCompany', 'users'])
            ->forUser(auth()->user())
            ->latest()
            ->paginate(12);

        $companies = GroupCompany::forUser(auth()->user())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $allUsers = User::with('groupCompany')
            ->forUser(auth()->user())
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'group_company_id']);

        return view('notification-groups.index', compact('groups', 'companies', 'allUsers'));
    }

    public function store(NotificationGroupStoreRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_notification_groups'), 403);
        $group = NotificationGroup::create($request->safe()->except('user_ids'));

        if ($userIds = $request->input('user_ids')) {
            $group->users()->sync($userIds);
        }

        ActivityLogger::log(
            action: 'created',
            module: 'notification_groups',
            recordId: $group->id,
            newValues: $request->safe()->except('user_ids'),
        );

        return redirect()->route('notification-groups.index')
            ->with('success', 'Notification group created successfully.');
    }

    public function update(NotificationGroupUpdateRequest $request, NotificationGroup $notificationGroup): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_notification_groups'), 403);
        $old = $notificationGroup->toArray();
        $notificationGroup->update($request->safe()->except('user_ids'));

        if ($request->has('user_ids')) {
            $notificationGroup->users()->sync($request->input('user_ids', []));
        }

        ActivityLogger::log(
            action: 'updated',
            module: 'notification_groups',
            recordId: $notificationGroup->id,
            oldValues: $old,
            newValues: $request->safe()->except('user_ids'),
        );

        return redirect()->route('notification-groups.index')
            ->with('success', 'Notification group updated successfully.');
    }

    public function destroy(NotificationGroup $notificationGroup): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_notification_groups'), 403);
        $notificationGroup->delete();

        ActivityLogger::log(
            action: 'deleted',
            module: 'notification_groups',
            recordId: $notificationGroup->id,
        );

        return redirect()->route('notification-groups.index')
            ->with('success', 'Notification group deleted successfully.');
    }
}
