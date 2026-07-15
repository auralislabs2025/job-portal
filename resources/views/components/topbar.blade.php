<button class="topbar-icon mobile-menu-btn" onclick="toggleSidebar()" aria-label="Menu">
    <i class="fa-solid fa-bars"></i>
</button>
<div class="topbar-left">
    <span class="topbar-company">ABN Corporation</span>
</div>
<div class="topbar-right">
    <div class="topbar-search" style="position:relative;">
        <input type="text" placeholder="Search..." style="padding:0.4rem 0.8rem; border:1.5px solid var(--gray-border); border-radius:50px; font-size:0.82rem; width:180px; background:var(--gray-bg);">
    </div>
    <button class="topbar-icon" onclick="toggleDarkMode()" aria-label="Dark mode">
        <i class="fa-solid fa-moon"></i>
    </button>
    <button class="topbar-icon" aria-label="Notifications">
        <i class="fa-solid fa-bell"></i>
        <span class="badge">5</span>
    </button>
    <div class="topbar-profile" onclick="event.stopPropagation();" style="cursor:pointer;position:relative;">
        <div class="topbar-avatar" onclick="document.getElementById('userDropdown').classList.toggle('show')">{{ Str::of(Auth::user()->name)->substr(0, 2)->upper() }}</div>
        <span class="topbar-name" onclick="document.getElementById('userDropdown').classList.toggle('show')">{{ Auth::user()->name }}</span>
        <i class="fa-solid fa-chevron-down" style="font-size:0.65rem; color:var(--gray-text);" onclick="document.getElementById('userDropdown').classList.toggle('show')"></i>
        <div id="userDropdown" class="user-dropdown" onclick="event.stopPropagation()">
            <a href="{{ route('settings.index') }}"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="{{ route('users.index') }}"><i class="fa-solid fa-user"></i> Profile</a>
            <hr>
            <form method="POST" action="{{ route('logout') }}" id="topbar-logout-form">
                @csrf
                <a href="#" onclick="event.preventDefault(); document.getElementById('topbar-logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </form>
        </div>
    </div>
</div>
