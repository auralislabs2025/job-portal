// Shared application state
const APP_STATE = {
    darkMode: false
};

// Group companies data
const GROUP_COMPANIES = [
    { id: 'GC001', name: 'Transport Division', manager: 'Ahmed Al Rashid', employees: 1250, openJobs: 8, pendingApproval: 3, recentHiring: 12, icon: '🚛' },
    { id: 'GC002', name: 'Petroleum Division', manager: 'Mohammed Al Qasimi', employees: 890, openJobs: 5, pendingApproval: 2, recentHiring: 7, icon: '⛽' },
    { id: 'GC003', name: 'Equipment Division', manager: 'Suresh Patel', employees: 670, openJobs: 4, pendingApproval: 1, recentHiring: 5, icon: '⚙️' },
    { id: 'GC004', name: 'Steel & Engineering', manager: 'David Chen', employees: 1500, openJobs: 12, pendingApproval: 5, recentHiring: 18, icon: '🏗️' },
    { id: 'GC005', name: 'Steel Fabrication', manager: 'Ravi Menon', employees: 430, openJobs: 3, pendingApproval: 0, recentHiring: 4, icon: '🔩' },
    { id: 'GC006', name: "Bhavan's Public School", manager: 'Dr. Lakshmi Iyer', employees: 320, openJobs: 6, pendingApproval: 2, recentHiring: 8, icon: '🏫' },
    { id: 'GC007', name: 'Property Developers', manager: 'Faisal Al Mahmoud', employees: 210, openJobs: 2, pendingApproval: 1, recentHiring: 3, icon: '🏢' },
    { id: 'GC008', name: 'International General Trading', manager: 'Omar Hassan', employees: 560, openJobs: 7, pendingApproval: 4, recentHiring: 9, icon: '🌐' },
    { id: 'GC009', name: 'MEDCORP Trading', manager: 'Dr. Priya Sharma', employees: 180, openJobs: 3, pendingApproval: 1, recentHiring: 2, icon: '💊' },
    { id: 'GC010', name: 'International Transport', manager: 'Carlos Mendez', employees: 780, openJobs: 9, pendingApproval: 3, recentHiring: 11, icon: '🚢' }
];

// Notification groups data
const NOTIFICATION_GROUPS = [
    { id: 'NG001', name: 'Job Approval Team', groupCompany: 'Transport Division', members: ['Ahmed Al Rashid', 'Rajesh Kumar', 'Sarah Wilson'], type: 'approval' },
    { id: 'NG002', name: 'Recruitment Team', groupCompany: 'Transport Division', members: ['Rajesh Kumar', 'Priya Menon', 'John Dsouza'], type: 'recruitment' },
    { id: 'NG003', name: 'Management Review', groupCompany: 'Petroleum Division', members: ['Mohammed Al Qasimi', 'Rajesh Kumar', 'Fatima Hassan'], type: 'management' },
    { id: 'NG004', name: 'Recruitment Team', groupCompany: "Bhavan's Public School", members: ['Dr. Lakshmi Iyer', 'Rajesh Kumar', 'Anita Desai'], type: 'recruitment' },
    { id: 'NG005', name: 'Job Approval Team', groupCompany: 'Steel & Engineering', members: ['David Chen', 'Rajesh Kumar', 'Michael Tan'], type: 'approval' },
    { id: 'NG006', name: 'Executive Approval', groupCompany: 'International General Trading', members: ['Omar Hassan', 'Rajesh Kumar'], type: 'management' }
];

const NOTIFICATION_GROUPS_SEED_VERSION = '1';

function getNotificationGroups() {
    const stored = localStorage.getItem('abn_notification_groups');
    const seedVersion = localStorage.getItem('abn_notification_groups_seed_version');
    if (stored && seedVersion === NOTIFICATION_GROUPS_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    localStorage.setItem('abn_notification_groups', JSON.stringify(NOTIFICATION_GROUPS));
    localStorage.setItem('abn_notification_groups_seed_version', NOTIFICATION_GROUPS_SEED_VERSION);
    return NOTIFICATION_GROUPS;
}

function saveNotificationGroups(groups) {
    localStorage.setItem('abn_notification_groups', JSON.stringify(groups));
}

function generateNotificationGroupId() {
    return 'NG' + Date.now().toString(36).toUpperCase();
}

window.getNotificationGroups = getNotificationGroups;
window.saveNotificationGroups = saveNotificationGroups;
window.generateNotificationGroupId = generateNotificationGroupId;

// ---------------------------------------------------------------------------
// User settings (profile + notification preferences)
// ---------------------------------------------------------------------------

// Settings holds general, system-level preferences only.
// Personal identity (name/email/role) is owned by the Users screen — see getCurrentUser().
const DEFAULT_SETTINGS = {
    notifyNewApplications: true,
    notifyJobApprovals: true,
    notifyWeeklyReports: false,
    darkMode: false
};

function getSettings() {
    const stored = localStorage.getItem('abn_settings');
    if (stored) {
        try { return { ...DEFAULT_SETTINGS, ...JSON.parse(stored) }; } catch(e) {}
    }
    return { ...DEFAULT_SETTINGS };
}

function saveSettings(settings) {
    localStorage.setItem('abn_settings', JSON.stringify(settings));
}

function syncProfileUI() {
    const currentUser = getCurrentUser();
    const nameEl = document.querySelector('.topbar-name');
    const avatarEl = document.querySelector('.topbar-avatar');
    if (nameEl) nameEl.textContent = currentUser.name;
    if (avatarEl) avatarEl.textContent = getInitials(currentUser.name);
}

window.getSettings = getSettings;
window.saveSettings = saveSettings;
window.syncProfileUI = syncProfileUI;

// Jobs dummy data
const JOBS = [
    { id: 'JOB001', title: 'Senior Fleet Manager', groupCompany: 'Transport Division', type: 'Full-time', location: 'Dubai, UAE', status: 'published', applications: 24, postedDate: '2025-06-15', deadline: '2025-07-15', salary: 'AED 25,000 - 35,000', notificationGroups: ['NG001', 'NG002'] },
    { id: 'JOB002', title: 'Logistics Coordinator', groupCompany: 'Transport Division', type: 'Full-time', location: 'Abu Dhabi, UAE', status: 'pending', applications: 0, postedDate: '2025-06-28', deadline: '2025-07-28', salary: 'AED 12,000 - 18,000', notificationGroups: ['NG001'] },
    { id: 'JOB003', title: 'Petroleum Engineer', groupCompany: 'Petroleum Division', type: 'Full-time', location: 'Kuwait City', status: 'approved', applications: 18, postedDate: '2025-06-10', deadline: '2025-08-01', salary: 'AED 30,000 - 45,000', notificationGroups: ['NG003'] },
    { id: 'JOB004', title: 'Heavy Equipment Operator', groupCompany: 'Equipment Division', type: 'Full-time', location: 'Sharjah, UAE', status: 'draft', applications: 0, postedDate: '2025-07-01', deadline: '2025-07-30', salary: 'AED 8,000 - 12,000', notificationGroups: [] },
    { id: 'JOB005', title: 'Structural Engineer', groupCompany: 'Steel & Engineering', type: 'Full-time', location: 'Dubai, UAE', status: 'published', applications: 32, postedDate: '2025-05-20', deadline: '2025-07-20', salary: 'AED 20,000 - 28,000', notificationGroups: ['NG005'] },
    { id: 'JOB006', title: 'Math Teacher - Secondary', groupCompany: "Bhavan's Public School", type: 'Full-time', location: 'Dubai, UAE', status: 'pending', applications: 5, postedDate: '2025-06-25', deadline: '2025-08-15', salary: 'AED 10,000 - 15,000', notificationGroups: ['NG004'] },
    { id: 'JOB007', title: 'Trading Manager', groupCompany: 'International General Trading', type: 'Full-time', location: 'Dubai, UAE', status: 'published', applications: 15, postedDate: '2025-06-05', deadline: '2025-07-25', salary: 'AED 22,000 - 30,000', notificationGroups: ['NG006'] },
    { id: 'JOB008', title: 'Fleet Maintenance Supervisor', groupCompany: 'International Transport', type: 'Full-time', location: 'Jebel Ali, UAE', status: 'draft', applications: 0, postedDate: '2025-06-18', deadline: '2025-07-18', salary: 'AED 15,000 - 20,000', notificationGroups: [] }
];

const JOBS_SEED_VERSION = '1';

function getJobs() {
    const stored = localStorage.getItem('abn_jobs');
    const seedVersion = localStorage.getItem('abn_jobs_seed_version');
    if (stored && seedVersion === JOBS_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    localStorage.setItem('abn_jobs', JSON.stringify(JOBS));
    localStorage.setItem('abn_jobs_seed_version', JOBS_SEED_VERSION);
    return JOBS;
}

function saveJobs(jobs) {
    localStorage.setItem('abn_jobs', JSON.stringify(jobs));
}

function generateJobId() {
    return 'JOB' + Date.now().toString(36).toUpperCase();
}

window.getJobs = getJobs;
window.saveJobs = saveJobs;
window.generateJobId = generateJobId;

// Applications dummy data
const APPLICATIONS = [
    { id: 'APP001', candidateName: 'James Anderson', jobTitle: 'Senior Fleet Manager', groupCompany: 'Transport Division', status: 'screening', appliedDate: '2025-06-20', email: 'james.anderson@email.com', phone: '+971 50 123 4567', resume: 'James_Anderson_Resume.pdf' },
    { id: 'APP002', candidateName: 'Maria Garcia', jobTitle: 'Petroleum Engineer', groupCompany: 'Petroleum Division', status: 'interview', appliedDate: '2025-06-15', email: 'maria.garcia@email.com', phone: '+971 55 234 5678', resume: 'Maria_Garcia_Resume.pdf' },
    { id: 'APP003', candidateName: 'Ahmed Hassan', jobTitle: 'Structural Engineer', groupCompany: 'Steel & Engineering', status: 'offer', appliedDate: '2025-06-01', email: 'ahmed.hassan@email.com', phone: '+971 52 345 6789', resume: 'Ahmed_Hassan_Resume.pdf' },
    { id: 'APP004', candidateName: 'Priya Sharma', jobTitle: 'Trading Manager', groupCompany: 'International General Trading', status: 'hired', appliedDate: '2025-05-25', email: 'priya.sharma@email.com', phone: '+971 56 456 7890', resume: 'Priya_Sharma_Resume.pdf' },
    { id: 'APP005', candidateName: 'Robert Kim', jobTitle: 'Senior Fleet Manager', groupCompany: 'Transport Division', status: 'rejected', appliedDate: '2025-06-18', email: 'robert.kim@email.com', phone: '+971 54 567 8901', resume: 'Robert_Kim_Resume.pdf' },
    { id: 'APP006', candidateName: 'Lisa Wang', jobTitle: 'Fleet Maintenance Supervisor', groupCompany: 'International Transport', status: 'pending', appliedDate: '2025-07-01', email: 'lisa.wang@email.com', phone: '+971 58 678 9012', resume: 'Lisa_Wang_Resume.pdf' }
];

const APPLICATIONS_SEED_VERSION = '1';

function getApplications() {
    const stored = localStorage.getItem('abn_applications');
    const seedVersion = localStorage.getItem('abn_applications_seed_version');
    if (stored && seedVersion === APPLICATIONS_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    localStorage.setItem('abn_applications', JSON.stringify(APPLICATIONS));
    localStorage.setItem('abn_applications_seed_version', APPLICATIONS_SEED_VERSION);
    return APPLICATIONS;
}

function saveApplications(applications) {
    localStorage.setItem('abn_applications', JSON.stringify(applications));
}

// Ordered candidate pipeline stages (excludes the terminal "rejected" state)
const APPLICATION_STAGES = ['pending', 'screening', 'interview', 'offer', 'hired'];

window.getApplications = getApplications;
window.saveApplications = saveApplications;
window.APPLICATION_STAGES = APPLICATION_STAGES;

// Activity logs
const ACTIVITY_LOGS = [
    { id: 'LOG001', action: 'Job Posted', details: 'Senior Fleet Manager - Transport Division', user: 'Rajesh Kumar', timestamp: '2025-07-05 14:30', type: 'job' },
    { id: 'LOG002', action: 'Application Received', details: 'James Anderson applied for Senior Fleet Manager', user: 'System', timestamp: '2025-07-05 13:15', type: 'application' },
    { id: 'LOG003', action: 'Job Approved', details: 'Petroleum Engineer - Petroleum Division', user: 'Mohammed Al Qasimi', timestamp: '2025-07-05 11:00', type: 'approval' },
    { id: 'LOG004', action: 'Candidate Shortlisted', details: 'Maria Garcia for Petroleum Engineer', user: 'Rajesh Kumar', timestamp: '2025-07-05 09:45', type: 'screening' },
    { id: 'LOG005', action: 'Interview Scheduled', details: 'Ahmed Hassan - Structural Engineer - July 10', user: 'Sarah Wilson', timestamp: '2025-07-04 16:20', type: 'interview' }
];





function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (!sidebar) return;
    const isOpen = sidebar.classList.toggle('open');
    let backdrop = document.querySelector('.sidebar-backdrop');
    if (isOpen) {
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.className = 'sidebar-backdrop';
            backdrop.addEventListener('click', closeSidebar);
            document.body.appendChild(backdrop);
        }
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    } else if (backdrop) {
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');
    if (sidebar) sidebar.classList.remove('open');
    if (backdrop) backdrop.classList.remove('show');
    document.body.style.overflow = '';
}

function applyDarkMode(enabled) {
    APP_STATE.darkMode = enabled;
    document.body.classList.toggle('dark-mode', enabled);
    document.querySelectorAll('.topbar-icon[aria-label="Dark mode"] i').forEach(icon => {
        icon.className = enabled ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
    });
}

function toggleDarkMode() {
    const settings = getSettings();
    settings.darkMode = !settings.darkMode;
    saveSettings(settings);
    applyDarkMode(settings.darkMode);
}

window.applyDarkMode = applyDarkMode;
window.toggleDarkMode = toggleDarkMode;

function openModal(id) {
    const overlay = document.getElementById(id);
    if (overlay) overlay.style.display = 'flex';
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    if (overlay) overlay.style.display = 'none';
}

window.openModal = openModal;
window.closeModal = closeModal;

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        padding: 12px 20px; border-radius: 8px; color: white;
        font-weight: 600; font-size: 0.85rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        animation: slideInRight 0.3s ease;
        background: ${type === 'success' ? '#1B7A3D' : type === 'error' ? '#C0392B' : '#1A3A5C'};
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = '0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    if (typeof getSettings === 'function') {
        applyDarkMode(getSettings().darkMode);
    }

    // Close user dropdown on outside click
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.user-dropdown.show').forEach(function (dd) {
            if (!dd.contains(e.target) && !dd.previousElementSibling?.contains(e.target)) {
                dd.classList.remove('show');
            }
        });
    });
});


// Group Companies data management
const DEFAULT_GROUP_COMPANIES = [
    { id: 'GC001', name: 'Transport Division', icon: '🚛', manager: 'Ahmed Al Rashid', employees: 1250, openJobs: 8, pendingApproval: 3, recentHiring: 12, address: 'Sheikh Zayed Road', city: 'Dubai', country: 'UAE', description: 'Core logistics and fleet management division.' },
    { id: 'GC002', name: 'Petroleum Division', icon: '⛽', manager: 'Mohammed Al Qasimi', employees: 890, openJobs: 5, pendingApproval: 2, recentHiring: 7, address: 'Al Ahmadi', city: 'Kuwait City', country: 'Kuwait', description: 'Exploration and trading of petroleum products.' },
    { id: 'GC003', name: 'Equipment Division', icon: '⚙️', manager: 'Suresh Patel', employees: 670, openJobs: 4, pendingApproval: 1, recentHiring: 5, address: 'Industrial Area 4', city: 'Sharjah', country: 'UAE', description: 'Heavy equipment sales, rental and servicing.' },
    { id: 'GC004', name: 'Steel & Engineering', icon: '🏗️', manager: 'David Chen', employees: 1500, openJobs: 12, pendingApproval: 5, recentHiring: 18, address: 'Jebel Ali Industrial Zone', city: 'Dubai', country: 'UAE', description: 'Structural steel manufacturing and engineering services.' },
    { id: 'GC005', name: 'Steel Fabrication', icon: '🔩', manager: 'Ravi Menon', employees: 430, openJobs: 3, pendingApproval: 0, recentHiring: 4, address: 'Musaffah Industrial Area', city: 'Abu Dhabi', country: 'UAE', description: 'Custom steel fabrication and welding solutions.' },
    { id: 'GC006', name: "Bhavan's Public School", icon: '🏫', manager: 'Dr. Lakshmi Iyer', employees: 320, openJobs: 6, pendingApproval: 2, recentHiring: 8, address: 'Al Qusais', city: 'Dubai', country: 'UAE', description: 'K-12 educational institution serving the community.' },
    { id: 'GC007', name: 'Property Developers', icon: '🏢', manager: 'Faisal Al Mahmoud', employees: 210, openJobs: 2, pendingApproval: 1, recentHiring: 3, address: 'Business Bay', city: 'Dubai', country: 'UAE', description: 'Residential and commercial real estate development.' },
    { id: 'GC008', name: 'International General Trading', icon: '🌐', manager: 'Omar Hassan', employees: 560, openJobs: 7, pendingApproval: 4, recentHiring: 9, address: 'Port Saeed', city: 'Dubai', country: 'UAE', description: 'Import, export and general trading across the region.' },
    { id: 'GC009', name: 'MEDCORP Trading', icon: '💊', manager: 'Dr. Priya Sharma', employees: 180, openJobs: 3, pendingApproval: 1, recentHiring: 2, address: 'Dubai Healthcare City', city: 'Dubai', country: 'UAE', description: 'Distribution of pharmaceuticals and medical supplies.' },
    { id: 'GC010', name: 'International Transport', icon: '🚢', manager: 'Carlos Mendez', employees: 780, openJobs: 9, pendingApproval: 3, recentHiring: 11, address: 'Jebel Ali Free Zone', city: 'Dubai', country: 'UAE', description: 'Cross-border freight and shipping logistics.' }
];

const GROUP_COMPANIES_SEED_VERSION = '2';

function getGroupCompanies() {
    const stored = localStorage.getItem('abn_group_companies');
    const seedVersion = localStorage.getItem('abn_group_companies_seed_version');
    if (stored && seedVersion === GROUP_COMPANIES_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    // First load, or the built-in default list changed since last seed: reseed defaults.
    localStorage.setItem('abn_group_companies', JSON.stringify(DEFAULT_GROUP_COMPANIES));
    localStorage.setItem('abn_group_companies_seed_version', GROUP_COMPANIES_SEED_VERSION);
    return DEFAULT_GROUP_COMPANIES;
}

function saveGroupCompanies(companies) {
    localStorage.setItem('abn_group_companies', JSON.stringify(companies));
}

function generateCompanyId() {
    return 'GC' + Date.now().toString(36).toUpperCase();
}

window.getGroupCompanies = getGroupCompanies;
window.saveGroupCompanies = saveGroupCompanies;
window.generateCompanyId = generateCompanyId;

// ---------------------------------------------------------------------------
// Users & Roles / Permissions data management
// ---------------------------------------------------------------------------

const PERMISSIONS_LIST = [
    { id: 'manage_users', label: 'Manage Users', description: 'Add, edit, deactivate and delete users' },
    { id: 'manage_roles', label: 'Manage Roles & Permissions', description: 'Change what each role is allowed to do' },
    { id: 'manage_companies', label: 'Manage Group Companies', description: 'Add, edit and delete group companies' },
    { id: 'post_jobs', label: 'Post Jobs', description: 'Create and edit job postings' },
    { id: 'approve_jobs', label: 'Approve Jobs', description: 'Approve or reject pending job postings' },
    { id: 'view_applications', label: 'View Applications', description: 'View candidate applications' },
    { id: 'manage_candidates', label: 'Manage Candidates', description: 'Screen, shortlist and update candidate status' },
    { id: 'manage_notification_groups', label: 'Manage Notification Groups', description: 'Create and edit notification groups' },
    { id: 'view_activity_logs', label: 'View Activity Logs', description: 'View the system-wide activity log' },
    { id: 'manage_settings', label: 'Manage Settings', description: 'Change system and profile settings' }
];

const ROLES_SEED_VERSION = '2';

const DEFAULT_ROLES = [
    { id: 'super_admin', name: 'Super Admin', description: 'Full access to every module.', permissions: PERMISSIONS_LIST.map(p => p.id), builtIn: true },
    { id: 'hr_manager', name: 'HR Manager', description: 'Manages recruitment operations across all divisions.', permissions: ['manage_users', 'manage_companies', 'post_jobs', 'approve_jobs', 'view_applications', 'manage_candidates', 'manage_notification_groups', 'view_activity_logs', 'manage_settings'], builtIn: true },
    { id: 'recruiter', name: 'Recruiter', description: 'Posts jobs and manages candidates for their division.', permissions: ['post_jobs', 'view_applications', 'manage_candidates'], builtIn: true }
];

function getRoles() {
    const stored = localStorage.getItem('abn_roles');
    const seedVersion = localStorage.getItem('abn_roles_seed_version');
    if (stored && seedVersion === ROLES_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    localStorage.setItem('abn_roles', JSON.stringify(DEFAULT_ROLES));
    localStorage.setItem('abn_roles_seed_version', ROLES_SEED_VERSION);
    return DEFAULT_ROLES;
}

function saveRoles(roles) {
    localStorage.setItem('abn_roles', JSON.stringify(roles));
}

function getRoleByName(name) {
    return getRoles().find(r => r.name === name);
}

function roleHasPermission(roleName, permissionId) {
    const role = getRoleByName(roleName);
    return !!(role && role.permissions.includes(permissionId));
}

const USERS_SEED_VERSION = '2';

const DEFAULT_USERS = [
    { id: 'U001', name: 'Rajesh Kumar', email: 'rajesh.kumar@abncorporation.com', role: 'HR Manager', groupCompany: 'All', status: 'active', createdAt: '2024-01-10T09:00:00.000Z' },
    { id: 'U002', name: 'Sarah Wilson', email: 'sarah.wilson@abncorporation.com', role: 'Recruiter', groupCompany: 'Transport Division', status: 'active', createdAt: '2024-02-15T09:00:00.000Z' },
    { id: 'U003', name: 'Ahmed Al Rashid', email: 'ahmed.rashid@abncorporation.com', role: 'HR Manager', groupCompany: 'Transport Division', status: 'active', createdAt: '2024-01-20T09:00:00.000Z' },
    { id: 'U004', name: 'Mohammed Al Qasimi', email: 'mohammed.qasimi@abncorporation.com', role: 'HR Manager', groupCompany: 'Petroleum Division', status: 'active', createdAt: '2024-01-22T09:00:00.000Z' },
    { id: 'U005', name: 'Suresh Patel', email: 'suresh.patel@abncorporation.com', role: 'Recruiter', groupCompany: 'Equipment Division', status: 'active', createdAt: '2024-03-05T09:00:00.000Z' },
    { id: 'U006', name: 'Priya Menon', email: 'priya.menon@abncorporation.com', role: 'Recruiter', groupCompany: 'Transport Division', status: 'inactive', createdAt: '2024-04-11T09:00:00.000Z' },
    { id: 'U007', name: 'David Chen', email: 'david.chen@abncorporation.com', role: 'HR Manager', groupCompany: 'Steel & Engineering', status: 'active', createdAt: '2024-02-01T09:00:00.000Z' }
];

function getUsers() {
    const stored = localStorage.getItem('abn_users');
    const seedVersion = localStorage.getItem('abn_users_seed_version');
    if (stored && seedVersion === USERS_SEED_VERSION) {
        try { return JSON.parse(stored); } catch(e) {}
    }
    localStorage.setItem('abn_users', JSON.stringify(DEFAULT_USERS));
    localStorage.setItem('abn_users_seed_version', USERS_SEED_VERSION);
    return DEFAULT_USERS;
}

function saveUsers(users) {
    localStorage.setItem('abn_users', JSON.stringify(users));
}

function generateUserId() {
    return 'U' + Date.now().toString(36).toUpperCase();
}

function getInitials(name) {
    if (!name) return '?';
    const parts = name.trim().split(/\s+/);
    return ((parts[0]?.[0] || '') + (parts[1]?.[0] || '')).toUpperCase();
}

// The signed-in user for this demo is always this seeded account.
// Their name/email/role are managed on the Users screen, not in Settings.
const CURRENT_USER_ID = 'U001';

function getCurrentUser() {
    const users = getUsers();
    return users.find(u => u.id === CURRENT_USER_ID) || users[0] || { name: 'User', email: '', role: '' };
}

window.PERMISSIONS_LIST = PERMISSIONS_LIST;
window.getRoles = getRoles;
window.saveRoles = saveRoles;
window.getRoleByName = getRoleByName;
window.roleHasPermission = roleHasPermission;
window.getUsers = getUsers;
window.saveUsers = saveUsers;
window.generateUserId = generateUserId;
window.getCurrentUser = getCurrentUser;
window.getInitials = getInitials;