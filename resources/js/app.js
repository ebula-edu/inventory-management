/**
 * @file app.js
 * @description Application entry point: navigation, sidebar drawer, product manager, supplier manager, global events.
 */

import './bootstrap';
import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { initGlobalListeners } from './modules/events.js';
import { openEditProductModal, closeEditProductModal, filterProductsTable, initProductManager } from './modules/product-manager.js';
import { openAddSupplierModal, closeAddSupplierModal, openEditSupplierModal, closeEditSupplierModal, initSupplierManager } from './modules/supplier-manager.js';

// Expose on window object for Blade template inline event handlers
window.showPage               = showPage;
window.updateNavActiveStates  = updateNavActiveStates;
window.inventorySubPages      = inventorySubPages;
window.openSidebar            = openSidebar;
window.closeSidebar           = closeSidebar;
window.toggleSideInventory    = toggleSideInventory;
window.openEditProductModal   = openEditProductModal;
window.closeEditProductModal  = closeEditProductModal;
window.filterProductsTable    = filterProductsTable;
window.openAddSupplierModal   = openAddSupplierModal;
window.closeAddSupplierModal  = closeAddSupplierModal;
window.openEditSupplierModal  = openEditSupplierModal;
window.closeEditSupplierModal = closeEditSupplierModal;

// Bootstrap
const initAll = () => {
    initGlobalListeners();
    initProductManager();
    initSupplierManager();
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
    openEditProductModal,
    closeEditProductModal,
    filterProductsTable,
    openAddSupplierModal,
    closeAddSupplierModal,
    openEditSupplierModal,
    closeEditSupplierModal,
    initAll,
};