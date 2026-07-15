<x-app-layout>
    <x-slot name="header">
        <div class="page-header flex-between">
            <div>
                <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Users</span></div>
                <h2>Users & Roles</h2>
                <p>Manage system users, roles and permissions.</p>
            </div>
        </div>
    </x-slot>

    <div class="tabs">
        <button class="tab-btn active" id="tab-btn-users" onclick="switchTab('users')"><i class="fa-solid fa-users"></i> Users</button>
        @can('manage_roles')
            <button class="tab-btn" id="tab-btn-permissions" onclick="switchTab('permissions')"><i class="fa-solid fa-shield-halved"></i> Roles & Permissions</button>
        @endcan
    </div>

    <div class="tab-panel active" id="tab-users">
        <div class="flex-between" style="margin-bottom:1rem;">
            <div class="filters-bar" style="margin-bottom:0;">
                <input type="text" id="userSearch" placeholder="Search by name or email...">
                <select id="roleFilter">
                    <option value="">All Roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <select id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            @can('manage_users')
                <button class="btn btn-primary" onclick="openUserModal()">
                    <i class="fa-solid fa-user-plus"></i> Add User
                </button>
            @endcan
        </div>

        <div class="card">
            <div class="table-container">
                <table id="usersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Group Company</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-panel" id="tab-permissions">
        <div class="card">
            <div class="card-header">
                <h3>Roles & Permissions</h3>
                @can('manage_roles')
                    <button class="btn btn-sm btn-primary" onclick="document.getElementById('permissionsForm').requestSubmit()">
                        <i class="fa-solid fa-floppy-disk"></i> Save Permissions
                    </button>
                @endcan
            </div>
            <p class="text-muted mb-2">Tick the boxes to control what each role is allowed to do across the system.</p>
            <form method="POST" action="{{ route('users.permissions') }}" id="permissionsForm">
                @csrf
                @method('PUT')
                <div class="table-container">
                    <table class="perm-table">
                        <thead id="permTableHead">
                            <tr>
                                <th>Permission</th>
                                @foreach ($roles as $role)
                                    <th>{{ $role->name }}<div class="role-desc">{{ $role->description ?? '' }}</div></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody id="permTableBody">
                            @foreach ($permissions as $perm)
                                <tr>
                                    <td><strong>{{ $perm->name }}</strong><div class="role-desc">{{ $perm->description }}</div></td>
                                    @foreach ($roles as $role)
                                        <td>
                                            <input type="checkbox"
                                                name="permissions[{{ $role->id }}][{{ $perm->id }}]"
                                                value="1"
                                                {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}
                                                {{ $role->is_builtin ? 'disabled' : '' }}>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <x-modal-wrapper name="userModal" title="Add User">
        <form method="POST" action="{{ route('users.store') }}" id="userForm">
            @csrf
            <input type="hidden" name="_method" id="userFormMethod" value="POST">
            <input type="hidden" id="userId" name="user_id" value="">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" id="userName" class="form-control" required placeholder="e.g. John Doe">
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" id="userEmail" class="form-control" required placeholder="e.g. john.doe@abncorporation.com">
            </div>
            <div class="form-group" id="passwordFieldGroup">
                <label>Password *</label>
                <input type="password" name="password" id="userPassword" class="form-control" placeholder="Min 8 characters">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role_id" id="userRole" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Group Company</label>
                    <select name="group_company_id" id="userGroupCompany" class="form-control">
                        <option value="">All Companies</option>
                        @foreach (\App\Models\GroupCompany::orderBy('name')->get() as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="userStatus" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex-between">
                <button type="button" class="btn btn-outline" onclick="closeModal('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="userSubmitBtn">
                    <i class="fa-solid fa-floppy-disk"></i> Save User
                </button>
            </div>
        </form>
    </x-modal-wrapper>

    @push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('tab-btn-' + tab).classList.add('active');
        }

        const table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('users.data') }}',
                data: function (d) {
                    d.status_filter = $('#statusFilter').val();
                    d.role_filter = $('#roleFilter').val();
                }
            },
            columns: [
                { data: 'display_name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'role_name', name: 'role_name', searchable: false },
                { data: 'company_name', name: 'company_name', searchable: false },
                { data: 'status_badge', name: 'status', searchable: false, orderable: false },
                { data: 'actions', name: 'actions', searchable: false, orderable: false },
            ],
            pageLength: 15,
            order: [[0, 'asc']],
            language: { search: '', searchPlaceholder: 'Search...' },
            dom: '<"top"lf>rt<"bottom"ip>',
        });

        document.getElementById('statusFilter').addEventListener('change', function () {
            table.ajax.reload();
        });
        document.getElementById('roleFilter').addEventListener('change', function () {
            table.ajax.reload();
        });
        document.getElementById('userSearch').addEventListener('keyup', function () {
            table.search(this.value).draw();
        });

        window.openUserModal = function() {
            document.getElementById('userForm').action = '{{ route('users.store') }}';
            document.getElementById('userFormMethod').value = 'POST';
            document.getElementById('userId').value = '';
            document.querySelector('#userModal .modal-header h3').textContent = 'Add User';
            document.getElementById('userSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Save User';
            document.getElementById('passwordFieldGroup').style.display = 'block';
            document.getElementById('userPassword').required = true;
            document.getElementById('userForm').reset();
            openModal('userModal');
        };

        window.editUser = function(id) {
            const table = $('#usersTable').DataTable();
            const row = table.row(function (idx, data) {
                return data.id === id;
            });
            const user = row.data();
            if (!user) return;

            document.getElementById('userForm').action = '{{ url('users') }}/' + id;
            document.getElementById('userFormMethod').value = 'PUT';
            document.getElementById('userId').value = user.id;
            document.querySelector('#userModal .modal-header h3').textContent = 'Edit User';
            document.getElementById('userSubmitBtn').innerHTML = '<i class="fa-solid fa-save"></i> Update User';
            document.getElementById('passwordFieldGroup').style.display = 'none';
            document.getElementById('userPassword').required = false;
            document.getElementById('userPassword').value = '';

            const tmp = document.createElement('div');
            tmp.innerHTML = user.display_name || '';
            const rawName = tmp.textContent || tmp.innerText || '';

            document.getElementById('userName').value = rawName.trim();
            document.getElementById('userEmail').value = user.email || '';
            document.getElementById('userRole').value = user.role_id || '';
            document.getElementById('userGroupCompany').value = user.group_company_id || '';
            document.getElementById('userStatus').value = user.status || 'active';

            openModal('userModal');
        };
    </script>
    @endpush
</x-app-layout>
