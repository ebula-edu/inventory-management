{{-- Permanent left-sidebar navigation (desktop always visible; mobile: overlay drawer) --}}
<aside class="sidebar" id="sidebar" aria-label="Primary Navigation">

    {{-- Brand / Logo + Mobile Close Button --}}
    <div class="sidebar-header">
        <div class="sidebar-brand" onclick="window.showPage('dashboard')" role="button" tabindex="0" aria-label="Go to Dashboard">
            <div class="sidebar-brand-icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <span class="sidebar-brand-name">Inventory</span>
        </div>
        <button class="sidebar-close-btn" onclick="closeSidebar()" aria-label="Close sidebar drawer">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav" aria-label="Main Navigation">

        {{-- Dashboard --}}
        <a class="side-link {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}"
           data-page="dashboard"
           onclick="showPage('dashboard'); closeSidebar();"
           role="menuitem">
            <span class="side-link-inner">
                <i class="fa-solid fa-house"></i>
                <span class="side-link-label">Dashboard</span>
            </span>
        </a>

        {{-- Inventory accordion --}}
        <div class="side-group">
            <button class="side-link side-link-toggle {{ in_array(($activePage ?? ''), ['inventory', 'products', 'stock', 'stock-in', 'stock-out', 'inventory-management', 'low-stock']) ? 'active' : '' }}"
                    id="sideInventoryToggle"
                    onclick="toggleSideInventory()"
                    aria-expanded="{{ in_array(($activePage ?? ''), ['inventory', 'products', 'stock', 'stock-in', 'stock-out', 'inventory-management', 'low-stock']) ? 'true' : 'false' }}"
                    aria-controls="sideInventory">
                <span class="side-link-inner">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span class="side-link-label">Inventory</span>
                </span>
                <i class="fa-solid fa-chevron-down side-arrow" id="sideArrow"></i>
            </button>

            <div class="side-dropdown {{ in_array(($activePage ?? ''), ['inventory', 'products', 'stock', 'stock-in', 'stock-out', 'inventory-management', 'low-stock']) ? 'show' : '' }}"
                 id="sideInventory"
                 role="menu">
                <a class="side-sub-link {{ ($activePage ?? '') === 'inventory' ? 'active' : '' }}"
                   data-page="inventory"
                   onclick="showPage('inventory'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-layer-group"></i> Overview
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'products' ? 'active' : '' }}"
                   data-page="products"
                   onclick="showPage('products'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-tag"></i> Products
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'stock' ? 'active' : '' }}"
                   data-page="stock"
                   onclick="showPage('stock'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-warehouse"></i> Stock Management
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'stock-in' ? 'active' : '' }}"
                   data-page="stock-in"
                   onclick="showPage('stock-in'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-arrow-down-to-line"></i> Stock In
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'stock-out' ? 'active' : '' }}"
                   data-page="stock-out"
                   onclick="showPage('stock-out'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-arrow-up-from-line"></i> Stock Out
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'inventory-management' ? 'active' : '' }}"
                   data-page="inventory-management"
                   onclick="showPage('inventory-management'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-sliders"></i> Management
                </a>
                <a class="side-sub-link {{ ($activePage ?? '') === 'low-stock' ? 'active' : '' }}"
                   data-page="low-stock"
                   onclick="showPage('low-stock'); closeSidebar();"
                   role="menuitem">
                    <i class="fa-solid fa-triangle-exclamation"></i> Low Stock
                </a>
            </div>
        </div>

        {{-- Add Product --}}
        <a class="side-link {{ ($activePage ?? '') === 'add-product' ? 'active' : '' }}"
           data-page="add-product"
           onclick="showPage('add-product'); closeSidebar();"
           role="menuitem">
            <span class="side-link-inner">
                <i class="fa-solid fa-circle-plus"></i>
                <span class="side-link-label">Add Product</span>
            </span>
        </a>

        {{-- Suppliers --}}
        <a class="side-link {{ ($activePage ?? '') === 'suppliers' ? 'active' : '' }}"
           data-page="suppliers"
           onclick="showPage('suppliers'); closeSidebar();"
           role="menuitem">
            <span class="side-link-inner">
                <i class="fa-solid fa-truck"></i>
                <span class="side-link-label">Suppliers</span>
            </span>
        </a>

        {{-- Reports --}}
        <a class="side-link {{ ($activePage ?? '') === 'reports' ? 'active' : '' }}"
           data-page="reports"
           onclick="showPage('reports'); closeSidebar();"
           role="menuitem">
            <span class="side-link-inner">
                <i class="fa-solid fa-chart-simple"></i>
                <span class="side-link-label">Reports</span>
            </span>
        </a>

        {{-- Settings --}}
        <a class="side-link {{ ($activePage ?? '') === 'settings' ? 'active' : '' }}"
           data-page="settings"
           onclick="showPage('settings'); closeSidebar();"
           role="menuitem">
            <span class="side-link-inner">
                <i class="fa-solid fa-gear"></i>
                <span class="side-link-label">Settings</span>
            </span>
        </a>
    </nav>

    {{-- Sidebar footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-footer-info">
            <i class="fa-solid fa-circle-info"></i>
            <span>v1.0.0</span>
        </div>
    </div>

</aside>

{{-- Mobile overlay backdrop --}}
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()" aria-hidden="true"></div>
