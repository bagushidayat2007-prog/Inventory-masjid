<header class="topbar">
    <div style="display:flex; align-items:center; gap:12px;">
        <button class="hamburger" id="sidebarToggle" aria-label="Toggle menu">&#9776;</button>
        <span class="page-title">@yield('title', 'Dashboard')</span>
    </div>

    <div class="user-chip">
        <div class="user-meta" style="text-align:right;">
            <div class="name">{{ auth()->user()->nama }}</div>
            <div class="role">{{ auth()->user()->role_label }}</div>
        </div>
        <div class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
    </div>
</header>
