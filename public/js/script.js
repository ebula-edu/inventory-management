/**
 * @file script.js
 * @description Application orchestrator coordinating navigation, mobile drawer, and mega menu.
 * Modular components are organized within ./modules/.
 */

import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { toggleMegaMenu, closeMegaMenu } from './modules/mega-menu.js';
import { initGlobalListeners } from './modules/events.js';

// Expose on global window object for Blade template inline event handlers
window.showPage = (pageID) => {
    closeMegaMenu();
    showPage(pageID);
};
window.updateNavActiveStates = updateNavActiveStates;
window.inventorySubPages = inventorySubPages;
window.openSidebar = openSidebar;
window.closeSidebar = closeSidebar;
window.toggleSideInventory = toggleSideInventory;
window.toggleMegaMenu = toggleMegaMenu;
window.closeMegaMenu = closeMegaMenu;

// Initialize global event listeners
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGlobalListeners);
} else {
    initGlobalListeners();
}

export {
    showPage,
    updateNavActiveStates,
    openSidebar,
    closeSidebar,
    toggleSideInventory,
    toggleMegaMenu,
    closeMegaMenu,
    initGlobalListeners,
};