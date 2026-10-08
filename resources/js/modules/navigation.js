/**
 * @file navigation.js
 * @description Manages view navigation, tab transitions, and active state synchronization
 * across the sidebar, mobile tabs, and topbar title.
 */

/**
 * Human-readable page titles keyed by page ID.
 * @type {Record<string, string>}
 */
const PAGE_TITLES = {
    dashboard:              'Dashboard',
    inventory:              'Inventory Overview',
    products:               'Products',
    stock:                  'Stock Management',
    'stock-in':             'Stock In',
    'stock-out':            'Stock Out',
    'inventory-management': 'Inventory Management',
    'low-stock':            'Low Stock Alerts',
    'add-product':          'Add Product',
    suppliers:              'Suppliers',
    reports:                'Reports',
    settings:               'Settings',
};

/**
 * List of page IDs that belong to the Inventory accordion group.
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
 * Activates a target page section and synchronizes all navigation active states.
 *
 * @param {string} pageID - The DOM element ID of the target page container.
 * @returns {void}
 */
export function showPage(pageID) {
    document.querySelectorAll('.page').forEach((p) => {
        p.classList.remove('active');
    });

    const targetPage = document.getElementById(pageID);
    if (targetPage) {
        targetPage.classList.add('active');
    }

    updateNavActiveStates(pageID);
    updateTopbarTitle(pageID);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/**
 * Updates the topbar title text to reflect the current page.
 *
 * @param {string} pageID - The active page ID.
 * @returns {void}
 */
function updateTopbarTitle(pageID) {
    const titleEl = document.getElementById('topbarTitle');
    if (titleEl) {
        titleEl.textContent = PAGE_TITLES[pageID] ?? pageID;
    }
}

/**
 * Synchronizes active classes on sidebar links, sub-links, and mobile tabs.
 *
 * @param {string} pageID - The active page ID.
 * @returns {void}
 */
export function updateNavActiveStates(pageID) {
    const isInventory = inventorySubPages.includes(pageID);

    // Sidebar main links
    document.querySelectorAll('.sidebar .side-link').forEach((link) => {
        const pageAttr = link.getAttribute('data-page');
        if (link.id === 'sideInventoryToggle') {
            link.classList.toggle('active', isInventory);
            link.setAttribute('aria-expanded', String(isInventory));
        } else {
            link.classList.toggle('active', pageAttr === pageID);
        }
    });

    // Sidebar sub-links
    document.querySelectorAll('.side-sub-link').forEach((link) => {
        link.classList.toggle('active', link.getAttribute('data-page') === pageID);
    });

    // Keep inventory accordion open when sub-page is active
    const sideInventory = document.getElementById('sideInventory');
    if (sideInventory) {
        sideInventory.classList.toggle('show', isInventory);
    }

    // Mobile bottom tabs
    document.querySelectorAll('.mobile-tabs .tab').forEach((tab) => {
        const pageAttr = tab.getAttribute('data-page');
        if (pageAttr === 'inventory') {
            tab.classList.toggle('active', isInventory);
        } else {
            tab.classList.toggle('active', pageAttr === pageID);
        }
    });
}
