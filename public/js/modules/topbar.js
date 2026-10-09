/**
 * @file topbar.js
 * @description Manages the notification panel and admin profile dropdown in the topbar.
 * Fetches real notifications from /api/notifications and handles panel toggle lifecycle.
 */

// ---- Constants ----

/** How often (ms) to refresh the notification feed. */
const NOTIF_POLL_INTERVAL = 60_000;

/** Time formatting – relative human-readable labels. */
const RELATIVE_TIME = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

// ---- State ----

/** @type {ReturnType<typeof setInterval>|null} */
let notifPollTimer = null;

// ============================================================
// NOTIFICATION PANEL
// ============================================================

/**
 * Toggles the notification panel open or closed.
 *
 * @returns {void}
 */
export function toggleNotifPanel() {
    const panel = document.getElementById('notifPanel');
    const btn   = document.getElementById('notifBtn');

    if (!panel || !btn) return;

    const isOpen = !panel.hidden;

    if (isOpen) {
        closeNotifPanel();
    } else {
        // Close profile panel if open
        closeProfilePanel();
        panel.hidden = false;
        btn.setAttribute('aria-expanded', 'true');
        loadNotifications();
        panel.querySelector('button, a')?.focus();
    }
}

/**
 * Closes the notification panel.
 *
 * @returns {void}
 */
export function closeNotifPanel() {
    const panel = document.getElementById('notifPanel');
    const btn   = document.getElementById('notifBtn');

    if (panel) panel.hidden = true;
    if (btn)   btn.setAttribute('aria-expanded', 'false');
}

/**
 * Fetches notifications from the server and renders them in the panel.
 *
 * @returns {Promise<void>}
 */
async function loadNotifications() {
    const list    = document.getElementById('notifList');
    const badge   = document.getElementById('notifBadge');
    const loading = document.getElementById('notifLoading');

    if (!list) return;

    // Show loading state
    list.innerHTML = '';
    const loadEl = document.createElement('div');
    loadEl.className = 'notif-empty';
    loadEl.id = 'notifLoading';
    loadEl.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Loading notifications…</span>';
    list.appendChild(loadEl);

    try {
        const res  = await fetch('/api/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();

        // Update badge
        if (badge) {
            if (data.unread > 0) {
                badge.textContent = data.unread > 9 ? '9+' : String(data.unread);
                badge.hidden = false;
            } else {
                badge.hidden = true;
            }
        }

        // Render items
        list.innerHTML = '';

        if (!data.notifications || data.notifications.length === 0) {
            list.innerHTML = `
                <div class="notif-empty">
                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                    <span>All clear — no alerts right now</span>
                </div>`;
            return;
        }

        data.notifications.forEach(/** @param {Object} n */ (n) => {
            const item = document.createElement('div');
            item.className = 'notif-item';
            item.setAttribute('role', 'listitem');
            item.setAttribute('tabindex', '0');

            if (n.page) {
                item.setAttribute('aria-label', `Navigate to ${n.page}`);
                item.addEventListener('click', () => {
                    if (window.showPage) window.showPage(n.page);
                    closeNotifPanel();
                });
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        if (window.showPage) window.showPage(n.page);
                        closeNotifPanel();
                    }
                });
            }

            item.innerHTML = `
                <div class="notif-icon ${n.colour}" aria-hidden="true">
                    <i class="fa-solid ${n.icon}"></i>
                </div>
                <div class="notif-content">
                    <p class="notif-title">${escapeHtml(n.title)}</p>
                    <p class="notif-body">${escapeHtml(n.body)}</p>
                    <p class="notif-time">${relativeTime(n.time)}</p>
                </div>`;

            list.appendChild(item);
        });

    } catch (err) {
        list.innerHTML = `
            <div class="notif-empty">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <span>Could not load notifications</span>
            </div>`;
        console.warn('[topbar] Notification fetch failed:', err);
    }
}

// ============================================================
// PROFILE PANEL
// ============================================================

/**
 * Toggles the admin profile dropdown open or closed.
 *
 * @returns {void}
 */
export function toggleProfilePanel() {
    const panel = document.getElementById('profilePanel');
    const btn   = document.getElementById('profileBtn');

    if (!panel || !btn) return;

    const isOpen = !panel.hidden;

    if (isOpen) {
        closeProfilePanel();
    } else {
        closeNotifPanel();
        panel.hidden = false;
        btn.setAttribute('aria-expanded', 'true');
        updateSessionTime();
        panel.querySelector('button, a')?.focus();
    }
}

/**
 * Closes the profile dropdown.
 *
 * @returns {void}
 */
export function closeProfilePanel() {
    const panel = document.getElementById('profilePanel');
    const btn   = document.getElementById('profileBtn');

    if (panel) panel.hidden = true;
    if (btn)   btn.setAttribute('aria-expanded', 'false');
}

/**
 * Updates the session time display in the profile panel.
 *
 * @returns {void}
 */
function updateSessionTime() {
    const el = document.getElementById('sessionTime');
    if (!el) return;

    const sessionStartKey = '__inv_session_start';

    if (!sessionStorage.getItem(sessionStartKey)) {
        sessionStorage.setItem(sessionStartKey, Date.now().toString());
    }

    const start = parseInt(sessionStorage.getItem(sessionStartKey) ?? '0', 10);
    const elapsed = Math.floor((Date.now() - start) / 60_000);

    el.textContent = elapsed < 1
        ? 'Just started'
        : `${elapsed} min${elapsed !== 1 ? 's' : ''}`;
}

// ============================================================
// INITIALISATION
// ============================================================

/**
 * Sets up topbar: polls notifications on load, close-on-outside-click,
 * and Escape-key dismissal.
 *
 * @returns {void}
 */
export function initTopbar() {
    // Initial badge load (without opening panel)
    fetchBadgeCount();

    // Poll every minute
    notifPollTimer = setInterval(fetchBadgeCount, NOTIF_POLL_INTERVAL);

    // Close panels when clicking outside
    document.addEventListener('click', (e) => {
        const notifDropdown   = document.getElementById('notifDropdown');
        const profileDropdown = document.getElementById('profileDropdown');

        if (notifDropdown && !notifDropdown.contains(/** @type {Node} */ (e.target))) {
            closeNotifPanel();
        }
        if (profileDropdown && !profileDropdown.contains(/** @type {Node} */ (e.target))) {
            closeProfilePanel();
        }
    });

    // Escape key closes both
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeNotifPanel();
            closeProfilePanel();
        }
    });
}

/**
 * Silently fetches the notification count and updates the badge only.
 * Does not modify the panel list.
 *
 * @returns {Promise<void>}
 */
async function fetchBadgeCount() {
    const badge = document.getElementById('notifBadge');
    if (!badge) return;

    try {
        const res  = await fetch('/api/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return;

        const data = await res.json();

        if (data.unread > 0) {
            badge.textContent = data.unread > 9 ? '9+' : String(data.unread);
            badge.hidden = false;
        } else {
            badge.hidden = true;
        }
    } catch {
        // Silently fail — badge stays as-is
    }
}

// ============================================================
// HELPERS
// ============================================================

/**
 * Sanitizes a string for safe HTML insertion.
 *
 * @param {string} str
 * @returns {string}
 */
function escapeHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}

/**
 * Converts an ISO date string to a human-readable relative time label.
 *
 * @param {string} isoString
 * @returns {string}
 */
function relativeTime(isoString) {
    if (!isoString) return '';

    const diff = Math.round((new Date(isoString).getTime() - Date.now()) / 1000);
    const abs  = Math.abs(diff);

    if (abs < 60)   return 'Just now';
    if (abs < 3600) return RELATIVE_TIME.format(-Math.round(abs / 60), 'minute');
    if (abs < 86400)return RELATIVE_TIME.format(-Math.round(abs / 3600), 'hour');
    return RELATIVE_TIME.format(-Math.round(abs / 86400), 'day');
}
