<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="breadcrumb"><a href="{{ route('dashboard') }}">Home</a> <span>/</span> <span>Settings</span></div>
            <h2>Settings</h2>
        </div>
    </x-slot>

    <div class="card mb-2" style="max-width:600px;">
        <div class="flex-between">
            <div style="display:flex;align-items:center;gap:0.8rem;">
                <div class="topbar-avatar" style="width:44px;height:44px;font-size:0.9rem;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}</div>
                <div>
                    <div style="font-weight:700;color:var(--navy-deep);">{{ auth()->user()->name }}</div>
                    <div class="text-muted">{{ auth()->user()->email }}</div>
                    <div class="text-muted">{{ auth()->user()->role?->name ?? '—' }}</div>
                </div>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i> Edit in Users</a>
        </div>
        <p class="text-muted mt-1" style="margin-top:0.8rem;">Your name, email and role are managed centrally on the <a href="{{ route('users.index') }}">Users</a> screen. This page only controls your general preferences below.</p>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" id="settingsForm">
        @csrf
        @method('PUT')

        <div class="card mb-2" style="max-width:600px;">
            <h3>General Preferences</h3>
            <div class="form-group">
                <label>Notification Preferences</label>
                <div style="display:flex;flex-direction:column;gap:0.4rem;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="notify_new_applications" value="1" {{ $settings->notify_new_applications ? 'checked' : '' }}>
                        Email notifications for new applications
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="notify_job_approvals" value="1" {{ $settings->notify_job_approvals ? 'checked' : '' }}>
                        Job approval requests
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="notify_weekly_reports" value="1" {{ $settings->notify_weekly_reports ? 'checked' : '' }}>
                        Weekly recruitment reports
                    </label>
                </div>
            </div>
        </div>

        <div class="card mb-2" style="max-width:600px;">
            <h3>Appearance</h3>
            <div class="form-group" style="margin-bottom:0;">
                <label class="checkbox-label">
                    <input type="checkbox" name="dark_mode" value="1" id="darkModeCheckbox" {{ $settings->dark_mode ? 'checked' : '' }} onchange="toggleDarkMode()">
                    Enable dark mode
                </label>
                <small class="text-muted">Applies a dark theme across the whole app immediately, and is remembered next time you sign in.</small>
            </div>
        </div>

        <div class="card" style="max-width:600px;">
            <div class="flex-between">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('settingsForm').reset(); location.reload();"><i class="fa-solid fa-rotate-left"></i> Reset</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        // Ensure unchecked checkboxes send a value (Laravel checkbox convention)
        document.getElementById('settingsForm').addEventListener('submit', function() {
            document.querySelectorAll('#settingsForm input[type="checkbox"]').forEach(function(cb) {
                if (!cb.checked) {
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = cb.name;
                    hidden.value = '0';
                    cb.parentNode.insertBefore(hidden, cb);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
