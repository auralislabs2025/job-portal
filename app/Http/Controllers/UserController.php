<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserSetting;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('users.index', compact('roles', 'permissions'));
    }

    public function data(Request $request): JsonResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        $query = User::with(['role', 'groupCompany'])
            ->forUser(auth()->user())
            ->select('users.*');

        return DataTables::of($query)
            ->addColumn('display_name', fn (User $user) => '<div class="user-cell"><div class="user-avatar">' . e(strtoupper(substr($user->name, 0, 1)) . strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1))) . '</div>' . e($user->name) . '</div>')
            ->addColumn('role_name', fn (User $user) => $user->role?->name ?? '—')
            ->addColumn('company_name', fn (User $user) => $user->groupCompany?->name ?? '—')
            ->addColumn('status_badge', fn (User $user) => view('components.status-badge', ['status' => $user->status])->render())
            ->addColumn('actions', fn (User $user) => view('users.partials.actions', compact('user'))->render())
            ->filterColumn('role_name', function ($query, $keyword) {
                $query->whereHas('role', fn ($q) => $q->where('name', 'ilike', "%{$keyword}%"));
            })
            ->filterColumn('company_name', function ($query, $keyword) {
                $query->whereHas('groupCompany', fn ($q) => $q->where('name', 'ilike', "%{$keyword}%"));
            })
            ->filter(function ($query) use ($request) {
                if ($search = $request->input('search.value')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('users.name', 'ilike', "%{$search}%")
                          ->orWhere('users.email', 'ilike', "%{$search}%");
                    });
                }
                if ($status = $request->input('status_filter')) {
                    $query->where('users.status', $status);
                }
                if ($roleId = $request->input('role_filter')) {
                    $query->where('users.role_id', $roleId);
                }
            })
            ->rawColumns(['display_name', 'status_badge', 'actions'])
            ->make(true);
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        $data = $request->validated();
        $data['password'] = $data['password'] ? bcrypt($data['password']) : bcrypt('password');
        $data['email_verified_at'] = now();

        $user = User::create($data);

        UserSetting::create(['user_id' => $user->id]);

        ActivityLogger::log(
            action: 'created',
            module: 'users',
            recordId: $user->id,
            newValues: $request->safe()->except('password'),
        );

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        $data = $request->validated();
        $old = $user->toArray();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        ActivityLogger::log(
            action: 'updated',
            module: 'users',
            recordId: $user->id,
            oldValues: $old,
            newValues: $request->safe()->except('password'),
        );

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $old = $user->toArray();
        $user->delete();

        ActivityLogger::log(
            action: 'deleted',
            module: 'users',
            recordId: $user->id,
            oldValues: $old,
        );

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function updatePermissions(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermission('manage_roles')) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to manage roles.');
        }

        $data = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'in:on,1,true',
        ]);

        foreach ($data['permissions'] as $roleId => $perms) {
            $role = Role::find($roleId);
            if (!$role || $role->is_builtin) continue;

            $permissionIds = array_keys($perms);
            $role->permissions()->sync($permissionIds);
        }

        ActivityLogger::log(
            action: 'updated',
            module: 'roles',
            recordId: null,
            newValues: ['permissions_updated' => true],
        );

        return redirect()->route('users.index')
            ->with('success', 'Permissions updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_unless(auth()->user()->hasPermission('manage_users'), 403);
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot change your own status.');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        ActivityLogger::log(
            action: 'updated',
            module: 'users',
            recordId: $user->id,
            oldValues: ['status' => $user->status === 'active' ? 'inactive' : 'active'],
            newValues: ['status' => $user->status],
        );

        return redirect()->route('users.index')
            ->with('success', 'User status updated successfully.');
    }
}
