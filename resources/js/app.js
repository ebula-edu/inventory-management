/**
 * @file app.js
 * @description Application entry point bundling navigation, drawer sidebar, mega menu, product manager, and global events.
 */

import './bootstrap';
import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { toggleMegaMenu, closeMegaMenu } from './modules/mega-menu.js';
import { initGlobalListeners } from './modules/events.js';
import { openEditProductModal, closeEditProductModal, filterProductsTable, initProductManager } from './modules/product-manager.js';

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
window.openEditProductModal = openEditProductModal;
window.closeEditProductModal = closeEditProductModal;
window.filterProductsTable = filterProductsTable;

// Initialize global event listeners once DOM is ready
const initAll = () => {
    initGlobalListeners();
    initProductManager();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

export {
    showPage,
    updateNavActiveStates,
    openSidebar,
    closeSidebar,
    toggleSideInventory,
    toggleMegaMenu,
    closeMegaMenu,
    openEditProductModal,
    closeEditProductModal,
    filterProductsTable,
    initAll,
};