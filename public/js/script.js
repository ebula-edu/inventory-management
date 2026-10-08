/**
 * @file script.js
 * @description Application orchestrator: navigation, sidebar, topbar panels,
 * product manager, supplier manager, and global events.
 * All sub-modules are in ./modules/.
 */

import { showPage, updateNavActiveStates, inventorySubPages } from './modules/navigation.js';
import { openSidebar, closeSidebar, toggleSideInventory } from './modules/sidebar.js';
import { initGlobalListeners } from './modules/events.js';
import { openEditProductModal, closeEditProductModal, filterProductsTable, initProductManager } from './modules/product-manager.js';
import { openAddSupplierModal, closeAddSupplierModal, openEditSupplierModal, closeEditSupplierModal, initSupplierManager } from './modules/supplier-manager.js';
import { initTopbar, toggleNotifPanel, closeNotifPanel, toggleProfilePanel, closeProfilePanel } from './modules/topbar.js';

// ---- Window globals for Blade template inline handlers ----
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
window.toggleNotifPanel       = toggleNotifPanel;
window.closeNotifPanel        = closeNotifPanel;
window.toggleProfilePanel     = toggleProfilePanel;
window.closeProfilePanel      = closeProfilePanel;

// ---- Bootstrap all modules ----
const initAll = () => {
    initGlobalListeners();
    initProductManager();
    initSupplierManager();
    initTopbar();
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
    toggleNotifPanel,
    closeNotifPanel,
    toggleProfilePanel,
    closeProfilePanel,
    initAll,
};