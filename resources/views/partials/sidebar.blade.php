<aside class="sidebar" id="sidebar" aria-label="Mobile Navigation Drawer">
    <div class="sidebar-header">
        <span>Menu</span>
        <button class="close-sidebar" onclick="closeSidebar()" aria-label="Close Navigation">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a class="side-link {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}" data-page="dashboard" onclick="showPage('dashboard'); closeSidebar();">
            <span class="side-link-content"><i class="fa-solid fa-house"></i> Dashboard</span>
        </a>

        <div>
            <div class="side-link" id="sideInventoryToggle" onclick="toggleSideInventory()">
                <span class="side-link-content"><i class="fa-solid fa-boxes-stacked"></i> Inventory</span>
                <i class="fa-solid fa-chevron-down" id="sideArrow" style="font-size: 11px;"></i>
            </div>

            <div class="side-dropdown" id="sideInventory">
                <a data-page="inventory" onclick="showPage('inventory'); closeSidebar();">Inventory Overview</a>
                <a data-page="products" onclick="showPage('products'); closeSidebar();">Products</a>
                <a data-page="stock" onclick="showPage('stock'); closeSidebar();">Stock Management</a>
                <a data-page="stock-in" onclick="showPage('stock-in'); closeSidebar();">Stock In</a>
                <a data-page="stock-out" onclick="showPage('stock-out'); closeSidebar();">Stock Out</a>
                <a data-page="inventory-management" onclick="showPage('inventory-management'); closeSidebar();">Inventory Management</a>
                <a data-page="low-stock" onclick="showPage('low-stock'); closeSidebar();">Low Stock</a>
            </div>
        </div>

        <a class="side-link {{ ($activePage ?? '') === 'add-product' ? 'active' : '' }}" data-page="add-product" onclick="showPage('add-product'); closeSidebar();">
            <span class="side-link-content"><i class="fa-solid fa-plus"></i> Add Product</span>
        </a>

        <a class="side-link {{ ($activePage ?? '') === 'suppliers' ? 'active' : '' }}" data-page="suppliers" onclick="showPage('suppliers'); closeSidebar();">
            <span class="side-link-content"><i class="fa-solid fa-truck"></i> Suppliers</span>
        </a>

        <a class="side-link {{ ($activePage ?? '') === 'reports' ? 'active' : '' }}" data-page="reports" onclick="showPage('reports'); closeSidebar();">
            <span class="side-link-content"><i class="fa-solid fa-chart-simple"></i> Reports</span>
        </a>

        <a class="side-link {{ ($activePage ?? '') === 'settings' ? 'active' : '' }}" data-page="settings" onclick="showPage('settings'); closeSidebar();">
            <span class="side-link-content"><i class="fa-solid fa-gear"></i> Settings</span>
        </a>
    </nav>
</aside>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>
