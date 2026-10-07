/**
 * @file navigation.js
 * @description Manages single-page tab transitions, active navigation links, and desktop/mobile sync.
 */

/**
 * List of tab IDs associated with the Inventory section dropdown.
 * @type {string[]}
 */
export const inventorySubPages = [
    'inventory',
    'products',
    'stock',
    'stock-in',
    'stock-out',
    'inventory-management',
    'low-stock',
];

/**
 * Activates a designated view section and synchronizes active navigation classes.
 *
 * @param {string} pageID - The DOM element ID of the target page container.
 * @param {Function} [onBeforeChange] - Optional callback to run before view switch.
 * @returns {void}
 */
export function showPage(pageID, onBeforeChange) {
    if (typeof onBeforeChange === 'function') {
        onBeforeChange();
    }

    const pages = document.querySelectorAll('.page');
    pages.forEach((p) => {
        p.classList.remove('active');
    });

    const targetPage = document.getElementById(pageID);
    if (targetPage) {
        targetPage.classList.add('active');
    }

    updateNavActiveStates(pageID);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Updates desktop navigation items, mega-menu links, mobile sidebar, and bottom tabs.
 *
 * @param {string} pageID - The DOM element ID of the currently active page.
 * @returns {void}
 */
export function updateNavActiveStates(pageID) {
    const isInventory = inventorySubPages.includes(pageID);

    document.querySelectorAll('.desktop-nav .nav-link').forEach((link) => {
        const pageAttr = link.getAttribute('data-page');
        if (link.id === 'desktopInventoryTrigger') {
            link.classList.toggle('active', isInventory);
        } else {
            link.classList.toggle('active', pageAttr === pageID);
        }
    });

    document.querySelectorAll('#megaMenu a').forEach((link) => {
        link.classList.toggle('active', link.getAttribute('data-page') === pageID);
    });

    document.querySelectorAll('.sidebar .side-link').forEach((link) => {
        const pageAttr = link.getAttribute('data-page');
        link.classList.toggle('active', pageAttr === pageID);
    });

    document.querySelectorAll('.side-dropdown a').forEach((link) => {
        link.classList.toggle('active', link.getAttribute('data-page') === pageID);
    });

    const tabs = document.querySelectorAll('.mobile-tabs .tab');
    tabs.forEach((tab) => {
        const pageAttr = tab.getAttribute('data-page');
        if (pageAttr === 'inventory') {
            tab.classList.toggle('active', isInventory);
        } else {
            tab.classList.toggle('active', pageAttr === pageID);
        }
    });
}
