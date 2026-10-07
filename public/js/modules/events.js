/**
 * @file events.js
 * @description Registers global click outside and keyboard shortcuts for UI dismissal.
 */

import { closeMegaMenu } from './mega-menu.js';
import { closeSidebar } from './sidebar.js';

/**
 * Initializes outside-click and escape keyboard listeners.
 *
 * @returns {void}
 */
export function initGlobalListeners() {
    document.addEventListener('click', (event) => {
        const mega = document.getElementById('megaMenu');
        const inventoryItem = document.getElementById('desktopInventoryItem');

        if (mega && mega.classList.contains('show')) {
            if (inventoryItem && !inventoryItem.contains(/** @type {Node} */ (event.target))) {
                closeMegaMenu();
            }
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            closeMegaMenu();
        }
    });
}
