/**
 * @file app.js
 * @description Application entry point bundling navigation, drawer sidebar, mega menu, and global events.
 */

import './bootstrap';
import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { toggleMegaMenu, closeMegaMenu } from './modules/mega-menu.js';
import { initGlobalListeners } from './modules/events.js';

// Expose on window object for backwards-compatible inline event handlers in Blade templates
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

// Initialize global event listeners once DOM is ready
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