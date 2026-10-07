/**
 * @file script.js
 * @description Application orchestrator coordinating navigation, mobile drawer, mega menu, and product catalog management.
 * Modular components are organized within ./modules/.
 */

import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { toggleMegaMenu, closeMegaMenu } from './modules/mega-menu.js';
import { initGlobalListeners } from './modules/events.js';
import { openEditProductModal, closeEditProductModal, filterProductsTable, initProductManager } from './modules/product-manager.js';

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
window.openEditProductModal = openEditProductModal;
window.closeEditProductModal = closeEditProductModal;
window.filterProductsTable = filterProductsTable;

// Initialize global event listeners
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