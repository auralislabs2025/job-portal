<div class="sidebar-brand">
    <div class="brand-icon">ABN</div>
    <div>
        <div class="brand-text">ABN Corporation</div>
        <div class="brand-sub">Recruitment System</div>
    </div>
</div>
<nav class="sidebar-nav">
    @canany(['view_dashboard', 'manage_companies', 'manage_users'])
        <div class="sidebar-section">Main Menu</div>
    @endcanany
    <x-sidebar-link route="dashboard" permission="view_dashboard" icon="fa-grid-2" pattern="dashboard" label="Dashboard" id="nav-dashboard" />
    <x-sidebar-link route="group-companies.index" permission="manage_companies" icon="fa-building" pattern="group-companies.*" label="Group Companies" id="nav-group-companies" />
    <x-sidebar-link route="users.index" permission="manage_users" icon="fa-users" pattern="users.*" label="Users" id="nav-users" />

    @canany(['post_jobs', 'view_applications'])
        <div class="sidebar-section">Recruitment</div>
    @endcanany
    <x-sidebar-link route="jobs.index" permission="post_jobs" icon="fa-briefcase" pattern="jobs.*" label="Jobs" id="nav-jobs" />
    <x-sidebar-link route="applications.index" permission="view_applications" icon="fa-file-lines" pattern="applications.*" label="Applications" id="nav-applications" />

    @canany(['manage_notification_groups', 'view_activity_logs', 'manage_settings'])
        <div class="sidebar-section">Administration</div>
    @endcanany
    <x-sidebar-link route="notification-groups.index" permission="manage_notification_groups" icon="fa-bell" pattern="notification-groups.*" label="Notification Groups" id="nav-notification-groups" />
    <x-sidebar-link route="activity-logs.index" permission="view_activity_logs" icon="fa-clock-rotate-left" pattern="activity-logs.*" label="Activity Logs" id="nav-activity-logs" />
    <x-sidebar-link route="settings.index" permission="manage_settings" icon="fa-gear" pattern="settings.*" label="Settings" id="nav-settings" />
</nav>
<div class="sidebar-footer">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>
</div>
