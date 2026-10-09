{{--
    Topbar — slim header bar with:
    · Hamburger (mobile only)
    · Dynamic page title
    · Notification panel (real DB data via /api/notifications)
    · Admin profile dropdown
--}}
<header class="topbar" id="topbar" role="banner">

    {{-- Mobile hamburger --}}
    <button class="hamburger" id="hamburgerBtn"
            onclick="event.stopPropagation(); openSidebar()"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="sidebar">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>

    {{-- Current page title --}}
    <h1 class="topbar-title" id="topbarTitle">Dashboard</h1>

    {{-- Right-side controls --}}
    <div class="topbar-controls" role="group" aria-label="Topbar actions">

        {{-- Notification button + panel --}}
        <div class="topbar-dropdown" id="notifDropdown">
            <button class="topbar-icon-btn"
                    id="notifBtn"
                    aria-label="Notifications"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="notifPanel"
                    onclick="toggleNotifPanel()">
                <i class="fa-solid fa-bell" aria-hidden="true"></i>
                <span class="notif-badge" id="notifBadge" hidden aria-live="polite" aria-atomic="true">0</span>
            </button>

            {{-- Notification panel --}}
            <div class="dropdown-panel" id="notifPanel" role="region" aria-label="Notifications" hidden>
                <div class="dropdown-panel-header">
                    <span class="dropdown-panel-title">Notifications</span>
                    <button class="dropdown-panel-close" onclick="closeNotifPanel()" aria-label="Close notifications">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="notif-list" id="notifList" role="list">
                    {{-- Populated by JS --}}
                    <div class="notif-empty" id="notifLoading">
                        <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                        <span>Loading…</span>
                    </div>
                </div>

                <div class="dropdown-panel-footer">
                    <button class="notif-view-all" onclick="showPage('low-stock'); closeNotifPanel();">
                        View all low stock alerts
                    </button>
                </div>
            </div>
        </div>

        {{-- Profile dropdown --}}
        <div class="topbar-dropdown" id="profileDropdown">
            <button class="topbar-profile-btn"
                    id="profileBtn"
                    aria-label="Admin profile menu"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="profilePanel"
                    onclick="toggleProfilePanel()">
                <div class="topbar-avatar" aria-hidden="true">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span class="topbar-profile-name">{{ session('admin_name', 'Admin') }}</span>
                <i class="fa-solid fa-chevron-down topbar-profile-caret" aria-hidden="true"></i>
            </button>

            {{-- Profile panel --}}
            <div class="dropdown-panel dropdown-panel-right" id="profilePanel" role="dialog" aria-label="Profile menu" hidden>
                <div class="dropdown-panel-header">
                    <span class="dropdown-panel-title">Account</span>
                    <button class="dropdown-panel-close" onclick="closeProfilePanel()" aria-label="Close profile menu">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>

                {{-- Admin info --}}
                <div class="profile-info">
                    <div class="profile-avatar-lg" aria-hidden="true">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="profile-details">
                        <p class="profile-name">{{ session('admin_name', 'System Administrator') }}</p>
                        <p class="profile-role">
                            <span class="role-badge">
                                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                                System Admin
                            </span>
                        </p>
                        <p class="profile-email">{{ session('admin_email', 'admin@inventory.local') }}</p>
                    </div>
                </div>

                {{-- System stats --}}
                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-label">Active Items</span>
                        <span class="profile-stat-value">{{ number_format($totalItems ?? 0) }}</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-label">Low Stock</span>
                        <span class="profile-stat-value" style="{{ ($lowStockCount ?? 0) > 0 ? 'color: #dc2626; font-weight: 700;' : '' }}">{{ number_format($lowStockCount ?? 0) }}</span>
                    </div>
                </div>

                {{-- Quick actions --}}
                <nav class="profile-nav" aria-label="Profile navigation">
                    <button class="profile-nav-item" onclick="showPage('settings'); closeProfilePanel();">
                        <i class="fa-solid fa-gear" aria-hidden="true"></i>
                        System Settings
                    </button>
                    <button class="profile-nav-item" onclick="showPage('reports'); closeProfilePanel();">
                        <i class="fa-solid fa-chart-simple" aria-hidden="true"></i>
                        Reports
                    </button>
                </nav>

                <div class="dropdown-panel-footer">
                    <button class="profile-logout-btn" disabled title="Authentication not configured">
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        Sign Out
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- /.topbar-controls --}}

</header>
