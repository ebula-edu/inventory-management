/**
 * @file sidebar.js
 * @description Manages the left navigation drawer (mobile: slide-in, desktop: always visible).
 */

/**
 * Opens the mobile navigation drawer.
 *
 * @returns {void}
 */
export function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const hamburger = document.getElementById('hamburgerBtn');

    if (sidebar) {
        sidebar.classList.add('active');
    }
    if (overlay) {
        overlay.classList.add('active');
    }
    if (hamburger) {
        hamburger.setAttribute('aria-expanded', 'true');
    }
    document.body.classList.add('sidebar-open');
}

/**
 * Closes the mobile navigation drawer.
 *
 * @returns {void}
 */
export function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const hamburger = document.getElementById('hamburgerBtn');

    if (sidebar) {
        sidebar.classList.remove('active');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
    if (hamburger) {
        hamburger.setAttribute('aria-expanded', 'false');
    }
    document.body.classList.remove('sidebar-open');
}

/**
 * Toggles the inventory accordion section inside the sidebar.
 *
 * @returns {void}
 */
export function toggleSideInventory() {
    const menu = document.getElementById('sideInventory');
    const toggle = document.getElementById('sideInventoryToggle');

    if (menu) {
        const isOpen = menu.classList.toggle('show');
        if (toggle) {
            toggle.setAttribute('aria-expanded', String(isOpen));
        }
    }
}
