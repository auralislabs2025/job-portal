<x-app-layout>
    <x-slot name="header">
        <div class="page-header flex-between">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Notification Groups</span></div>
                <h2>Notification Groups</h2>
                <p>Manage approval and recruitment notification groups per group company.</p>
            </div>
            @can('manage_notification_groups')
                <button class="btn btn-primary" onclick="openNotificationGroupModal()">
                    <i class="fa-solid fa-plus"></i> Create Group
                </button>
            @endcan
        </div>
    </x-slot>

    @if($groups->count())
        <div class="company-grid" id="groupsGrid">
            @foreach ($groups as $group)
                <div class="card ng-card">
                    <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:0.6rem;">
                        <div class="stat-icon blue" style="font-size:1.3rem;">🔔</div>
                        <div>
                            <h3 style="font-size:1rem;font-weight:700;color:var(--navy-deep);">{{ $group->name }}</h3>
                            <p style="font-size:0.78rem;color:var(--gray-text);">{{ $group->groupCompany?->name ?? '—' }}</p>
                        </div>
                    </div>
                    @if($group->description)
                        <p style="font-size:0.8rem;color:var(--gray-dark);margin-bottom:0.5rem;">{{ $group->description }}</p>
                    @endif
                    <p style="font-size:0.8rem;margin-bottom:0.4rem;">
                        <strong>{{ $group->users->count() }} member{{ $group->users->count() !== 1 ? 's' : '' }}</strong>
                    </p>
                    <div class="ng-members">
                        @forelse ($group->users as $member)
                            <span class="ng-member-chip">{{ $member->name }}</span>
                        @empty
                            <span class="text-muted" style="font-size:0.78rem;">No members yet</span>
                        @endforelse
                    </div>
                    @can('manage_notification_groups')
                        <div style="margin-top:0.8rem;">
                            <button class="btn btn-sm btn-outline" onclick="editNotificationGroup({{ $group->id }})"><i class="fa-solid fa-pen"></i> Edit</button>
                            <form method="POST" action="{{ route('notification-groups.destroy', $group) }}" style="display:inline" onsubmit="return confirm('Delete {{ $group->name }}? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    @endcan
                </div>
            @endforeach
        </div>

        <x-pagination :paginator="$groups" />
    @else
        <x-empty-state icon="fa-bell" message="No notification groups found." actionLabel="Create your first group" actionUrl="#" onclick="openNotificationGroupModal()" />
    @endif

    {{-- Add/Edit Modal --}}
    <x-modal-wrapper name="notificationGroupModal" title="Create Notification Group">
        <form method="POST" action="{{ route('notification-groups.store') }}" id="notificationGroupForm">
            @csrf
            <input type="hidden" name="_method" id="notificationGroupFormMethod" value="POST">
            <input type="hidden" id="notificationGroupId" value="">
            <div class="form-group">
                <label>Group Name *</label>
                <input type="text" name="name" id="notificationGroupName" class="form-control" required placeholder="e.g. Job Approval Team">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Group Company *</label>
                    <select name="group_company_id" id="notificationGroupCompany" class="form-control" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" id="notificationGroupDescription" class="form-control" placeholder="Brief description">
                </div>
            </div>
            <div class="form-group">
                <label>Members</label>
                    <select name="user_ids[]" id="notificationGroupMembers" class="form-control" multiple style="height:120px;">
                    @foreach ($allUsers as $user)
                        <option value="{{ $user->id }}" data-company-id="{{ $user->group_company_id ?? '0' }}">{{ $user->name }} ({{ $user->groupCompany?->name ?? 'All' }})</option>
                    @endforeach
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple members.</small>
            </div>
            <div class="flex-between">
                <button type="button" class="btn btn-outline" onclick="closeModal('notificationGroupModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="notificationGroupSubmitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save Group
                </button>
            </div>
        </form>
    </x-modal-wrapper>

    @push('scripts')
    <script>
        const groupsData = @json($groups->items());

        function filterMembersByCompany() {
            const companyId = document.getElementById('notificationGroupCompany').value;
            const memberSelect = document.getElementById('notificationGroupMembers');
            Array.from(memberSelect.options).forEach(opt => {
                opt.style.display = !companyId || opt.dataset.companyId === companyId || opt.dataset.companyId === '0' ? '' : 'none';
            });
            // Clear selection for hidden options
            Array.from(memberSelect.options).forEach(opt => {
                if (opt.style.display === 'none') opt.selected = false;
            });
        }

        document.getElementById('notificationGroupCompany').addEventListener('change', filterMembersByCompany);

        window.openNotificationGroupModal = function() {
            document.getElementById('notificationGroupForm').action = '{{ route('notification-groups.store') }}';
            document.getElementById('notificationGroupFormMethod').value = 'POST';
            document.getElementById('notificationGroupId').value = '';
            document.querySelector('#notificationGroupModal .modal-header h3').textContent = 'Create Notification Group';
            document.getElementById('notificationGroupSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save Group';
            document.getElementById('notificationGroupForm').reset();
            // Show all members when no company selected
            Array.from(document.getElementById('notificationGroupMembers').options).forEach(opt => opt.style.display = '');
            openModal('notificationGroupModal');
        };

        window.editNotificationGroup = function(id) {
            const group = groupsData.find(g => g.id === id);
            if (!group) return;

            document.getElementById('notificationGroupForm').action = '{{ url('notification-groups') }}/' + id;
            document.getElementById('notificationGroupFormMethod').value = 'PUT';
            document.getElementById('notificationGroupId').value = group.id;
            document.querySelector('#notificationGroupModal .modal-header h3').textContent = 'Edit Notification Group';
            document.getElementById('notificationGroupSubmitBtn').innerHTML = '<i class="fa-solid fa-save"></i> Update Group';

            document.getElementById('notificationGroupName').value = group.name || '';
            document.getElementById('notificationGroupCompany').value = group.group_company_id || '';
            document.getElementById('notificationGroupDescription').value = group.description || '';

            const memberSelect = document.getElementById('notificationGroupMembers');
            const memberIds = (group.users || []).map(u => u.id);
            Array.from(memberSelect.options).forEach(opt => {
                opt.selected = memberIds.includes(parseInt(opt.value));
            });

            openModal('notificationGroupModal');
        };
    </script>
    @endpush
</x-app-layout>
