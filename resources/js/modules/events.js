/**
 * @file events.js
 * @description Registers global DOM event listeners for outside-clicks, keyboard shortcuts, and view dismissals.
 */

import { closeMegaMenu } from './mega-menu.js';
import { closeSidebar } from './sidebar.js';

/**
 * Attaches global document listeners for dismissal operations.
 *
 * @returns {void}
 */
export function initGlobalListeners() {
    // Dismiss mega-menu on clicks outside the trigger and container
    document.addEventListener('click', (event) => {
        const mega = document.getElementById('megaMenu');
        const inventoryItem = document.getElementById('desktopInventoryItem');

        if (mega && mega.classList.contains('show')) {
            if (inventoryItem && !inventoryItem.contains(/** @type {Node} */ (event.target))) {
                closeMegaMenu();
            }
        }
    });

    // Dismiss drawer and mega-menu on Escape key press
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            closeMegaMenu();
        }
    });
}
