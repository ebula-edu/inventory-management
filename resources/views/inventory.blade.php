<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

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
                <a class="nav-link active" data-page="dashboard" onclick="showPage('dashboard')">Dashboard</a>
            </div>

            <div class="nav-item" id="desktopInventoryItem">
                <a class="nav-link" id="desktopInventoryTrigger" onclick="toggleMegaMenu(event)">
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
                <a class="nav-link" data-page="add-product" onclick="showPage('add-product')">Add Product</a>
            </div>

            <div class="nav-item">
                <a class="nav-link" data-page="suppliers" onclick="showPage('suppliers')">Suppliers</a>
            </div>

            <div class="nav-item">
                <a class="nav-link" data-page="reports" onclick="showPage('reports')">Reports</a>
            </div>

            <div class="nav-item">
                <a class="nav-link" data-page="settings" onclick="showPage('settings')">Settings</a>
            </div>
        </nav>
    </header>

    <aside class="sidebar" id="sidebar" aria-label="Mobile Navigation Drawer">
        <div class="sidebar-header">
            <span>Menu</span>
            <button class="close-sidebar" onclick="closeSidebar()" aria-label="Close Navigation">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a class="side-link active" data-page="dashboard" onclick="showPage('dashboard'); closeSidebar();">
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

            <a class="side-link" data-page="add-product" onclick="showPage('add-product'); closeSidebar();">
                <span class="side-link-content"><i class="fa-solid fa-plus"></i> Add Product</span>
            </a>

            <a class="side-link" data-page="suppliers" onclick="showPage('suppliers'); closeSidebar();">
                <span class="side-link-content"><i class="fa-solid fa-truck"></i> Suppliers</span>
            </a>

            <a class="side-link" data-page="reports" onclick="showPage('reports'); closeSidebar();">
                <span class="side-link-content"><i class="fa-solid fa-chart-simple"></i> Reports</span>
            </a>

            <a class="side-link" data-page="settings" onclick="showPage('settings'); closeSidebar();">
                <span class="side-link-content"><i class="fa-solid fa-gear"></i> Settings</span>
            </a>
        </nav>
    </aside>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <main class="content">

        <section class="page active" id="dashboard">
            <div class="page-header">
                <h1>Dashboard</h1>
                <p>Overview of current stock, inventory status, and recent operations.</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Items</div>
                    <div class="stat-value">1,248</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Low Stock Alerts</div>
                    <div class="stat-value" style="color: #dc2626;">14</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Stock In Today</div>
                    <div class="stat-value" style="color: #16a34a;">+86</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Suppliers</div>
                    <div class="stat-value">32</div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 15px; margin-bottom: 12px; color: #0f172a;">Recent Inventory Activity</h3>
                <div class="table-responsive">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Wireless Mouse M100</td>
                                <td>SKU-8921</td>
                                <td>Electronics</td>
                                <td>142 pcs</td>
                                <td><span class="badge badge-success">In Stock</span></td>
                            </tr>
                            <tr>
                                <td>Mechanical Keyboard RGB</td>
                                <td>SKU-4310</td>
                                <td>Electronics</td>
                                <td>8 pcs</td>
                                <td><span class="badge badge-warning">Low Stock</span></td>
                            </tr>
                            <tr>
                                <td>USB-C Fast Cable 1m</td>
                                <td>SKU-1029</td>
                                <td>Accessories</td>
                                <td>320 pcs</td>
                                <td><span class="badge badge-success">In Stock</span></td>
                            </tr>
                            <tr>
                                <td>Ergonomic Desk Mat</td>
                                <td>SKU-5520</td>
                                <td>Office</td>
                                <td>45 pcs</td>
                                <td><span class="badge badge-success">In Stock</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="page" id="inventory">
            <div class="page-header">
                <h1>Inventory</h1>
                <p>Comprehensive list of stock items and warehouse locations.</p>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Location</th>
                                <th>Available</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>27" IPS Monitor</td>
                                <td>SKU-7721</td>
                                <td>Aisle 3, Bin B</td>
                                <td>24 pcs</td>
                                <td><span class="badge badge-success">Available</span></td>
                            </tr>
                            <tr>
                                <td>Bluetooth Speaker</td>
                                <td>SKU-9902</td>
                                <td>Aisle 1, Bin D</td>
                                <td>5 pcs</td>
                                <td><span class="badge badge-warning">Low</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="page" id="products">
            <div class="page-header">
                <h1>Products</h1>
                <p>Manage product catalog, details, categories, and pricing.</p>
            </div>
            <div class="card">
                <p style="color: #64748b; font-size: 14px;">Product listings catalog will display here.</p>
            </div>
        </section>

        <section class="page" id="stock">
            <div class="page-header">
                <h1>Stock Management</h1>
                <p>Audit, adjust, and monitor inventory quantities across warehouses.</p>
            </div>
            <div class="card">
                <p style="color: #64748b; font-size: 14px;">Stock management tools and count adjustments will display here.</p>
            </div>
        </section>

        <section class="page" id="stock-in">
            <div class="page-header">
                <h1>Stock In</h1>
                <p>Record newly arrived stock and supplier shipments.</p>
            </div>
            <div class="card">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="stockInSku">Product SKU or Name</label>
                        <input type="text" id="stockInSku" placeholder="e.g. SKU-8921">
                    </div>
                    <div class="form-group">
                        <label for="stockInQty">Quantity Received</label>
                        <input type="number" id="stockInQty" placeholder="e.g. 50">
                    </div>
                </div>
                <button class="btn btn-primary" type="button">Record Stock In</button>
            </div>
        </section>

        <section class="page" id="stock-out">
            <div class="page-header">
                <h1>Stock Out</h1>
                <p>Record dispatched items, customer orders, and internal usage.</p>
            </div>
            <div class="card">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="stockOutSku">Product SKU or Name</label>
                        <input type="text" id="stockOutSku" placeholder="e.g. SKU-8921">
                    </div>
                    <div class="form-group">
                        <label for="stockOutQty">Quantity Dispatched</label>
                        <input type="number" id="stockOutQty" placeholder="e.g. 10">
                    </div>
                </div>
                <button class="btn btn-primary" type="button">Record Stock Out</button>
            </div>
        </section>

        <section class="page" id="inventory-management">
            <div class="page-header">
                <h1>Inventory Management</h1>
                <p>Warehouse layout rules, stock thresholds, and unit controls.</p>
            </div>
            <div class="card">
                <p style="color: #64748b; font-size: 14px;">Inventory management configurations will display here.</p>
            </div>
        </section>

        <section class="page" id="low-stock">
            <div class="page-header">
                <h1>Low Stock Alerts</h1>
                <p>Items that have fallen below minimum threshold levels.</p>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Current Qty</th>
                                <th>Reorder Point</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mechanical Keyboard RGB</td>
                                <td style="color: #dc2626; font-weight: 600;">8 pcs</td>
                                <td>20 pcs</td>
                                <td><button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Reorder</button></td>
                            </tr>
                            <tr>
                                <td>Bluetooth Speaker</td>
                                <td style="color: #dc2626; font-weight: 600;">5 pcs</td>
                                <td>15 pcs</td>
                                <td><button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;">Reorder</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="page" id="add-product">
            <div class="page-header">
                <h1>Add Product</h1>
                <p>Register a new item into the inventory system.</p>
            </div>
            <div class="card">
                <form onsubmit="event.preventDefault(); alert('Product registered successfully (demo)!');">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="prodName">Product Name</label>
                            <input type="text" id="prodName" placeholder="e.g. Wireless Mouse" required>
                        </div>
                        <div class="form-group">
                            <label for="prodSku">SKU Code</label>
                            <input type="text" id="prodSku" placeholder="e.g. SKU-1002" required>
                        </div>
                        <div class="form-group">
                            <label for="prodCategory">Category</label>
                            <select id="prodCategory">
                                <option>Electronics</option>
                                <option>Accessories</option>
                                <option>Office Supplies</option>
                                <option>Hardware</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="prodQty">Initial Quantity</label>
                            <input type="number" id="prodQty" placeholder="0" min="0" required>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Save Product</button>
                </form>
            </div>
        </section>

        <section class="page" id="suppliers">
            <div class="page-header">
                <h1>Suppliers</h1>
                <p>Vendor contacts, purchase orders, and lead times.</p>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="simple-table">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Contact Email</th>
                                <th>Phone</th>
                                <th>Supplied Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Apex Electronics Co.</td>
                                <td>orders@apexelectronics.com</td>
                                <td>+1 (555) 019-2834</td>
                                <td>Peripherals, Cables</td>
                            </tr>
                            <tr>
                                <td>Global Pack & Ship Ltd.</td>
                                <td>support@globalpack.com</td>
                                <td>+1 (555) 014-9921</td>
                                <td>Packaging, Boxes</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="page" id="reports">
            <div class="page-header">
                <h1>Reports & Analytics</h1>
                <p>Monthly inventory turnover, movement analysis, and category share.</p>
            </div>

            <div class="card">
                <div class="chart-header">
                    <h3 style="font-size: 15px; color: #0f172a;">Stock Movement (2026)</h3>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-dot legend-blue"></span>
                            <span>Stock In</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot legend-gray"></span>
                            <span>Stock Out</span>
                        </div>
                    </div>
                </div>

                <div class="bar-chart-wrapper">
                    <div class="bar-chart">
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 65%;" title="Stock In: 65"></div>
                                <div class="chart-bar out" style="height: 40%;" title="Stock Out: 40"></div>
                            </div>
                            <span class="col-label">Jan</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 80%;" title="Stock In: 80"></div>
                                <div class="chart-bar out" style="height: 55%;" title="Stock Out: 55"></div>
                            </div>
                            <span class="col-label">Feb</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 45%;" title="Stock In: 45"></div>
                                <div class="chart-bar out" style="height: 70%;" title="Stock Out: 70"></div>
                            </div>
                            <span class="col-label">Mar</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 90%;" title="Stock In: 90"></div>
                                <div class="chart-bar out" style="height: 60%;" title="Stock Out: 60"></div>
                            </div>
                            <span class="col-label">Apr</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 75%;" title="Stock In: 75"></div>
                                <div class="chart-bar out" style="height: 50%;" title="Stock Out: 50"></div>
                            </div>
                            <span class="col-label">May</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 85%;" title="Stock In: 85"></div>
                                <div class="chart-bar out" style="height: 65%;" title="Stock Out: 65"></div>
                            </div>
                            <span class="col-label">Jun</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 60%;" title="Stock In: 60"></div>
                                <div class="chart-bar out" style="height: 45%;" title="Stock Out: 45"></div>
                            </div>
                            <span class="col-label">Jul</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 70%;" title="Stock In: 70"></div>
                                <div class="chart-bar out" style="height: 50%;" title="Stock Out: 50"></div>
                            </div>
                            <span class="col-label">Aug</span>
                        </div>
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: 95%;" title="Stock In: 95"></div>
                                <div class="chart-bar out" style="height: 80%;" title="Stock Out: 80"></div>
                            </div>
                            <span class="col-label">Sep</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 15px; margin-bottom: 16px; color: #0f172a;">Stock Value by Category</h3>
                <div class="progress-group">
                    <div class="progress-header">
                        <span>Electronics</span>
                        <span><strong>45%</strong> ($38,200)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: 45%;"></div>
                    </div>
                </div>
                <div class="progress-group">
                    <div class="progress-header">
                        <span>Accessories</span>
                        <span><strong>28%</strong> ($23,800)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: 28%; background: #0ea5e9;"></div>
                    </div>
                </div>
                <div class="progress-group">
                    <div class="progress-header">
                        <span>Office Supplies</span>
                        <span><strong>17%</strong> ($14,450)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: 17%; background: #10b981;"></div>
                    </div>
                </div>
                <div class="progress-group">
                    <div class="progress-header">
                        <span>Hardware</span>
                        <span><strong>10%</strong> ($8,500)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: 10%; background: #f59e0b;"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="page" id="settings">
            <div class="page-header">
                <h1>Settings</h1>
                <p>Configure system preferences, user roles, and alert notifications.</p>
            </div>
            <div class="card">
                <div class="form-group" style="max-width: 320px; margin-bottom: 14px;">
                    <label for="currencySelect">Default Currency</label>
                    <select id="currencySelect">
                        <option>USD ($)</option>
                        <option>EUR (€)</option>
                        <option>GBP (£)</option>
                        <option>PHP (₱)</option>
                    </select>
                </div>
                <div class="form-group" style="max-width: 320px; margin-bottom: 16px;">
                    <label for="lowStockThreshold">Default Low Stock Alert Threshold</label>
                    <input type="number" id="lowStockThreshold" value="10">
                </div>
                <button class="btn btn-primary" type="button" onclick="alert('Settings saved (demo)!')">Save Changes</button>
            </div>
        </section>

    </main>

    <nav class="mobile-tabs" aria-label="Mobile Navigation Bar">
        <a class="tab active" data-page="dashboard" onclick="showPage('dashboard')">
            <i class="fa-solid fa-house tab-icon"></i>
            <span>Home</span>
        </a>

        <a class="tab" data-page="inventory" onclick="showPage('inventory')">
            <i class="fa-solid fa-boxes-stacked tab-icon"></i>
            <span>Inventory</span>
        </a>

        <a class="tab" data-page="add-product" onclick="showPage('add-product')">
            <i class="fa-solid fa-plus tab-icon"></i>
            <span>Add</span>
        </a>

        <a class="tab" data-page="reports" onclick="showPage('reports')">
            <i class="fa-solid fa-chart-simple tab-icon"></i>
            <span>Reports</span>
        </a>

        <a class="tab" data-page="settings" onclick="showPage('settings')">
            <i class="fa-solid fa-gear tab-icon"></i>
            <span>Settings</span>
        </a>
    </nav>

    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>