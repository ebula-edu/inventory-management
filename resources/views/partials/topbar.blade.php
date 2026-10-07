<header class="topbar">
    <button class="hamburger" id="hamburgerBtn" onclick="openSidebar()" aria-label="Open Navigation Menu">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="logo" onclick="showPage('dashboard')">
        <i class="fa-solid fa-boxes-stacked"></i>
        <span>Inventory System</span>
    </div>

    <nav class="desktop-nav" aria-label="Desktop Navigation">
        <div class="nav-item">
            <a class="nav-link {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}" data-page="dashboard" onclick="showPage('dashboard')">Dashboard</a>
        </div>

        <div class="nav-item" id="desktopInventoryItem">
            <a class="nav-link {{ in_array(($activePage ?? ''), ['inventory', 'products', 'stock', 'stock-in', 'stock-out', 'inventory-management', 'low-stock']) ? 'active' : '' }}" id="desktopInventoryTrigger" onclick="toggleMegaMenu(event)">
                Inventory <i class="fa-solid fa-chevron-down dropdown-caret"></i>
            </a>

            <div class="mega-menu" id="megaMenu">
                <div class="mega-column">
                    <h3>Inventory</h3>
                    <a data-page="inventory" onclick="showPage('inventory')">Inventory Overview</a>
                    <a data-page="products" onclick="showPage('products')">Products List</a>
                </div>

                <div class="mega-column">
                    <h3>Stock</h3>
                    <a data-page="stock" onclick="showPage('stock')">Stock Management</a>
                    <a data-page="stock-in" onclick="showPage('stock-in')">Stock In</a>
                    <a data-page="stock-out" onclick="showPage('stock-out')">Stock Out</a>
                </div>

                <div class="mega-column">
                    <h3>Management</h3>
                    <a data-page="inventory-management" onclick="showPage('inventory-management')">Management</a>
                    <a data-page="low-stock" onclick="showPage('low-stock')">Low Stock Alert</a>
                </div>
            </div>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ ($activePage ?? '') === 'add-product' ? 'active' : '' }}" data-page="add-product" onclick="showPage('add-product')">Add Product</a>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ ($activePage ?? '') === 'suppliers' ? 'active' : '' }}" data-page="suppliers" onclick="showPage('suppliers')">Suppliers</a>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ ($activePage ?? '') === 'reports' ? 'active' : '' }}" data-page="reports" onclick="showPage('reports')">Reports</a>
        </div>

        <div class="nav-item">
            <a class="nav-link {{ ($activePage ?? '') === 'settings' ? 'active' : '' }}" data-page="settings" onclick="showPage('settings')">Settings</a>
        </div>
    </nav>
</header>
