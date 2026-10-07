@extends('layouts.app')

@section('title', 'Inventory System — Warehouse & Stock Management')

@section('content')

    {{-- 1. Dashboard View --}}
    <section class="page {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}" id="dashboard">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Overview of current stock, inventory status, and recent operations.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Items</div>
                <div class="stat-value">{{ number_format($totalItems) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Low Stock Alerts</div>
                <div class="stat-value" style="color: #dc2626;">{{ number_format($lowStockCount) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Stock In Today</div>
                <div class="stat-value" style="color: #16a34a;">+{{ number_format($stockInToday) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Suppliers</div>
                <div class="stat-value">{{ number_format($suppliers->count()) }}</div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 15px; margin-bottom: 12px; color: #0f172a;">Recent Inventory Items</h3>
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
                        @forelse ($products->take(6) as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category }}</td>
                                <td>{{ number_format($product->quantity) }} pcs</td>
                                <td>
                                    <span class="badge {{ $product->quantity <= 0 ? 'badge-danger' : ($product->isLowStock() ? 'badge-warning' : 'badge-success') }}">
                                        {{ $product->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b;">No inventory products registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 2. Inventory Overview --}}
    <section class="page {{ ($activePage ?? '') === 'inventory' ? 'active' : '' }}" id="inventory">
        <div class="page-header">
            <h1>Inventory Overview</h1>
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
                        @forelse ($products as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->location ?? 'Warehouse Floor' }}</td>
                                <td>{{ number_format($product->quantity) }} pcs</td>
                                <td>
                                    <span class="badge {{ $product->quantity <= 0 ? 'badge-danger' : ($product->isLowStock() ? 'badge-warning' : 'badge-success') }}">
                                        {{ $product->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b;">No products available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 3. Products List --}}
    <section class="page {{ ($activePage ?? '') === 'products' ? 'active' : '' }}" id="products">
        <div class="page-header">
            <h1>Products List</h1>
            <p>Manage product catalog, unit pricing, categories, and SKU codes.</p>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Reorder Point</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td>{{ $product->category }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ number_format($product->quantity) }}</td>
                                <td>{{ number_format($product->reorder_point) }}</td>
                                <td>
                                    <span class="badge {{ $product->quantity <= 0 ? 'badge-danger' : ($product->isLowStock() ? 'badge-warning' : 'badge-success') }}">
                                        {{ $product->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #64748b;">No products found in the catalog.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 4. Stock Management --}}
    <section class="page {{ ($activePage ?? '') === 'stock' ? 'active' : '' }}" id="stock">
        <div class="page-header">
            <h1>Stock Management</h1>
            <p>Audit, adjust, and monitor inventory quantities across warehouses.</p>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Item Name</th>
                            <th>Current Qty</th>
                            <th>Reorder Threshold</th>
                            <th>Audit Status</th>
                            <th>Quick Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ $product->name }}</td>
                                <td style="{{ $product->isLowStock() ? 'color: #dc2626; font-weight: 600;' : '' }}">
                                    {{ number_format($product->quantity) }} pcs
                                </td>
                                <td>{{ number_format($product->reorder_point) }} pcs</td>
                                <td>
                                    <span class="badge {{ $product->quantity <= 0 ? 'badge-danger' : ($product->isLowStock() ? 'badge-warning' : 'badge-success') }}">
                                        {{ $product->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @if ($product->isLowStock())
                                        <form method="POST" action="{{ route('stock.reorder', $product->id) }}" style="display: inline;">
                                            @csrf
                                            <button class="btn btn-primary" type="submit" style="padding: 4px 10px; font-size: 12px;">
                                                Reorder
                                            </button>
                                        </form>
                                    @else
                                        <span style="color: #64748b; font-size: 12px;">Sufficient</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: #64748b;">No stock items recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 5. Stock In Form --}}
    <section class="page {{ ($activePage ?? '') === 'stock-in' ? 'active' : '' }}" id="stock-in">
        <div class="page-header">
            <h1>Stock In</h1>
            <p>Record newly arrived stock and supplier shipments into the warehouse.</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('stock.in') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="stockInSku">Product</label>
                        <select name="sku" id="stockInSku" required>
                            <option value="" disabled selected>Select a product to receive...</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->sku }}">
                                    {{ $p->sku }} — {{ $p->name }} (Current: {{ $p->quantity }} pcs)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stockInQty">Quantity Received</label>
                        <input type="number" name="quantity" id="stockInQty" min="1" placeholder="e.g. 50" required>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 14px; max-width: 500px;">
                    <label for="stockInNotes">Notes / PO Reference</label>
                    <input type="text" name="notes" id="stockInNotes" placeholder="e.g. Supplier Shipment PO-8841">
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top: 16px;">
                    <i class="fa-solid fa-arrow-down" style="margin-right: 6px;"></i> Record Stock In
                </button>
            </form>
        </div>
    </section>

    {{-- 6. Stock Out Form --}}
    <section class="page {{ ($activePage ?? '') === 'stock-out' ? 'active' : '' }}" id="stock-out">
        <div class="page-header">
            <h1>Stock Out</h1>
            <p>Record dispatched items, customer orders, and outbound warehouse movements.</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('stock.out') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="stockOutSku">Product</label>
                        <select name="sku" id="stockOutSku" required>
                            <option value="" disabled selected>Select a product to dispatch...</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->sku }}">
                                    {{ $p->sku }} — {{ $p->name }} (Available: {{ $p->quantity }} pcs)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="stockOutQty">Quantity Dispatched</label>
                        <input type="number" name="quantity" id="stockOutQty" min="1" placeholder="e.g. 10" required>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 14px; max-width: 500px;">
                    <label for="stockOutNotes">Dispatch Notes / Order Number</label>
                    <input type="text" name="notes" id="stockOutNotes" placeholder="e.g. Sales Order #1042">
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top: 16px;">
                    <i class="fa-solid fa-arrow-up" style="margin-right: 6px;"></i> Record Stock Out
                </button>
            </form>
        </div>
    </section>

    {{-- 7. Inventory Management --}}
    <section class="page {{ ($activePage ?? '') === 'inventory-management' ? 'active' : '' }}" id="inventory-management">
        <div class="page-header">
            <h1>Inventory Management</h1>
            <p>Warehouse layout rules, stock thresholds, and unit controls.</p>
        </div>
        <div class="card">
            <h3 style="font-size: 15px; margin-bottom: 12px; color: #0f172a;">Warehouse Guidelines & Configuration</h3>
            <p style="color: #475569; font-size: 14px; line-height: 1.6; margin-bottom: 16px;">
                Stock levels are automatically checked against the assigned reorder point. Items below the threshold will trigger system alerts and appear in the Low Stock section for immediate reordering.
            </p>
            <div class="table-responsive">
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Active Products</th>
                            <th>Total Units</th>
                            <th>Default Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products->groupBy('category') as $category => $items)
                            <tr>
                                <td><strong>{{ $category }}</strong></td>
                                <td>{{ $items->count() }} items</td>
                                <td>{{ number_format($items->sum('quantity')) }} pcs</td>
                                <td>{{ $items->first()->location ?? 'General Storage' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 8. Low Stock Alerts --}}
    <section class="page {{ ($activePage ?? '') === 'low-stock' ? 'active' : '' }}" id="low-stock">
        <div class="page-header">
            <h1>Low Stock Alerts</h1>
            <p>Items that have fallen below minimum safety threshold levels.</p>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Current Qty</th>
                            <th>Reorder Point</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $low)
                            <tr>
                                <td><strong>{{ $low->name }}</strong> (<code>{{ $low->sku }}</code>)</td>
                                <td style="color: #dc2626; font-weight: 600;">{{ number_format($low->quantity) }} pcs</td>
                                <td>{{ number_format($low->reorder_point) }} pcs</td>
                                <td>
                                    <form method="POST" action="{{ route('stock.reorder', $low->id) }}" style="display: inline;">
                                        @csrf
                                        <button class="btn btn-primary" type="submit" style="padding: 4px 10px; font-size: 12px;">
                                            Reorder Restock
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #16a34a; padding: 20px;">
                                    <i class="fa-solid fa-circle-check" style="margin-right: 6px;"></i> All items currently have sufficient stock.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 9. Add Product Form --}}
    <section class="page {{ ($activePage ?? '') === 'add-product' ? 'active' : '' }}" id="add-product">
        <div class="page-header">
            <h1>Add Product</h1>
            <p>Register a new inventory item into the SQLite database catalog.</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="prodName">Product Name</label>
                        <input type="text" name="name" id="prodName" placeholder="e.g. Wireless Ergonomic Mouse" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prodSku">SKU Code</label>
                        <input type="text" name="sku" id="prodSku" placeholder="e.g. SKU-9102" value="{{ old('sku') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prodCategory">Category</label>
                        <select name="category" id="prodCategory" required>
                            <option value="Electronics" {{ old('category') === 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Accessories" {{ old('category') === 'Accessories' ? 'selected' : '' }}>Accessories</option>
                            <option value="Office Supplies" {{ old('category') === 'Office Supplies' ? 'selected' : '' }}>Office Supplies</option>
                            <option value="Hardware" {{ old('category') === 'Hardware' ? 'selected' : '' }}>Hardware</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prodLocation">Warehouse Location</label>
                        <input type="text" name="location" id="prodLocation" placeholder="e.g. Aisle 3, Bin C" value="{{ old('location') }}">
                    </div>
                    <div class="form-group">
                        <label for="prodQty">Initial Quantity</label>
                        <input type="number" name="quantity" id="prodQty" placeholder="0" min="0" value="{{ old('quantity', 0) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="prodPrice">Unit Price ($)</label>
                        <input type="number" name="price" id="prodPrice" step="0.01" min="0" placeholder="0.00" value="{{ old('price', '0.00') }}">
                    </div>
                </div>
                <button class="btn btn-primary" type="submit" style="margin-top: 16px;">
                    <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Save Product to Database
                </button>
            </form>
        </div>
    </section>

    {{-- 10. Suppliers Directory --}}
    <section class="page {{ ($activePage ?? '') === 'suppliers' ? 'active' : '' }}" id="suppliers">
        <div class="page-header">
            <h1>Suppliers</h1>
            <p>Vendor contacts, partner directories, and supplied merchandise.</p>
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
                        @forelse ($suppliers as $sup)
                            <tr>
                                <td><strong>{{ $sup->name }}</strong></td>
                                <td><a href="mailto:{{ $sup->email }}">{{ $sup->email }}</a></td>
                                <td>{{ $sup->phone }}</td>
                                <td>{{ $sup->supplied_items }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b;">No suppliers currently registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 11. Reports & Analytics --}}
    <section class="page {{ ($activePage ?? '') === 'reports' ? 'active' : '' }}" id="reports">
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
            <h3 style="font-size: 15px; margin-bottom: 16px; color: #0f172a;">Stock Distribution by Category</h3>
            @php
                $categoryCounts = $products->groupBy('category')->map->count();
                $totalCatProducts = max($products->count(), 1);
                $colors = ['#2563eb', '#0ea5e9', '#10b981', '#f59e0b', '#8b5cf6'];
                $colorIndex = 0;
            @endphp
            @foreach ($categoryCounts as $catName => $count)
                @php
                    $pct = round(($count / $totalCatProducts) * 100);
                    $color = $colors[$colorIndex % count($colors)];
                    $colorIndex++;
                @endphp
                <div class="progress-group" style="margin-bottom: 12px;">
                    <div class="progress-header">
                        <span>{{ $catName }}</span>
                        <span><strong>{{ $pct }}%</strong> ({{ $count }} items)</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $pct }}%; background: {{ $color }};"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 12. Settings --}}
    <section class="page {{ ($activePage ?? '') === 'settings' ? 'active' : '' }}" id="settings">
        <div class="page-header">
            <h1>Settings</h1>
            <p>Configure system preferences, user roles, and alert thresholds.</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <div class="form-group" style="max-width: 320px; margin-bottom: 14px;">
                    <label for="currencySelect">Default Currency</label>
                    <select name="currency" id="currencySelect" required>
                        <option value="USD ($)" {{ $currency === 'USD ($)' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR (€)" {{ $currency === 'EUR (€)' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GBP (£)" {{ $currency === 'GBP (£)' ? 'selected' : '' }}>GBP (£)</option>
                        <option value="PHP (₱)" {{ $currency === 'PHP (₱)' ? 'selected' : '' }}>PHP (₱)</option>
                    </select>
                </div>
                <div class="form-group" style="max-width: 320px; margin-bottom: 16px;">
                    <label for="lowStockThreshold">Default Low Stock Alert Threshold</label>
                    <input type="number" name="low_stock_threshold" id="lowStockThreshold" value="{{ $lowStockThreshold }}" min="1" max="1000" required>
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i> Save System Settings
                </button>
            </form>
        </div>
    </section>

@endsection