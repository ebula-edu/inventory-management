<nav class="mobile-tabs" aria-label="Mobile Navigation Bar">
    <a class="tab {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}" data-page="dashboard" onclick="showPage('dashboard')">
        <i class="fa-solid fa-house tab-icon"></i>
        <span>Home</span>
    </a>

    <a class="tab {{ in_array(($activePage ?? ''), ['inventory', 'products', 'stock', 'stock-in', 'stock-out', 'inventory-management', 'low-stock']) ? 'active' : '' }}" data-page="inventory" onclick="showPage('inventory')">
        <i class="fa-solid fa-boxes-stacked tab-icon"></i>
        <span>Inventory</span>
    </a>

    <a class="tab {{ ($activePage ?? '') === 'add-product' ? 'active' : '' }}" data-page="add-product" onclick="showPage('add-product')">
        <i class="fa-solid fa-plus tab-icon"></i>
        <span>Add</span>
    </a>

    <a class="tab {{ ($activePage ?? '') === 'reports' ? 'active' : '' }}" data-page="reports" onclick="showPage('reports')">
        <i class="fa-solid fa-chart-simple tab-icon"></i>
        <span>Reports</span>
    </a>

    <a class="tab {{ ($activePage ?? '') === 'settings' ? 'active' : '' }}" data-page="settings" onclick="showPage('settings')">
        <i class="fa-solid fa-gear tab-icon"></i>
        <span>Settings</span>
    </a>
</nav>
