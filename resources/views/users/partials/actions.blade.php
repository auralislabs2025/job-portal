@can('manage_users')
    <button class="btn btn-sm btn-outline" onclick="editUser({{ $user->id }})" title="Edit"><i class="fa-solid fa-pen"></i></button>
    @if ($user->id !== Auth::id())
        <form method="POST" action="{{ route('users.status', $user) }}" style="display:inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline" title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                <i class="fa-solid {{ $user->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
            </button>
        </form>
        <form method="POST" action="{{ route('users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Delete {{ $user->name }}? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
        </form>
    @endif
@endcan
