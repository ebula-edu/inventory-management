/**
 * @file sidebar.js
 * @description Manages mobile navigation drawer open, close, and accordion behaviors.
 */

/**
 * Opens the mobile drawer navigation.
 *
 * @returns {void}
 */
export function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (sidebar) {
        sidebar.classList.add('active');
    }
    if (overlay) {
        overlay.classList.add('active');
    }
    document.body.classList.add('sidebar-open');
}

/**
 * Closes the mobile drawer navigation.
 *
 * @returns {void}
 */
export function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (sidebar) {
        sidebar.classList.remove('active');
    }
    if (overlay) {
        overlay.classList.remove('active');
    }
    document.body.classList.remove('sidebar-open');
}

/**
 * Toggles the inventory submenu accordion inside the mobile sidebar drawer.
 *
 * @returns {void}
 */
export function toggleSideInventory() {
    const menu = document.getElementById('sideInventory');
    const arrow = document.getElementById('sideArrow');

    if (menu) {
        const isOpen = menu.classList.toggle('show');
        if (arrow) {
            arrow.className = isOpen ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down';
        }
    }
}
