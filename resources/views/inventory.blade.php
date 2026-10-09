@extends('layouts.app')

@section('title', 'Inventory System — Warehouse & Stock Management')

@section('content')

    {{-- 1. Dashboard View --}}
    <section class="page {{ ($activePage ?? 'dashboard') === 'dashboard' ? 'active' : '' }}" id="dashboard">
        <div class="page-header">
            <div class="page-header-text">
                <h1>Dashboard</h1>
                <p>Real-time overview of stock levels, inventory status, and recent movements.</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon blue">
                    <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value">{{ number_format($totalItems) }}</div>
                    <div class="stat-sub">{{ number_format($totalQuantity) }} total units</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon red">
                    <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="stat-label">Low Stock Alerts</div>
                    <div class="stat-value text-danger">{{ number_format($lowStockCount) }}</div>
                    <div class="stat-sub">Items below reorder point</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon green">
                    <i class="fa-solid fa-arrow-down-to-line" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="stat-label">Stock In Today</div>
                    <div class="stat-value text-success">+{{ number_format($stockInToday) }}</div>
                    <div class="stat-sub">Units received today</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card-icon amber">
                    <i class="fa-solid fa-truck" aria-hidden="true"></i>
                </div>
                <div>
                    <div class="stat-label">Suppliers</div>
                    <div class="stat-value">{{ number_format($suppliers->count()) }}</div>
                    <div class="stat-sub">Active vendor contacts</div>
                </div>
            </div>
        </div>

        <div class="card">
            <p class="card-title">
                <i class="fa-solid fa-clock-rotate-left" aria-hidden="true" style="color:var(--brand-600);margin-right:7px;"></i>
                Recent Inventory Items
            </p>
            <div class="table-responsive">
                <table class="simple-table" aria-label="Recent inventory items">
                    <thead>
                        <tr>
                            <th scope="col">Item Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Category</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products->take(6) as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><code>{{ $product->sku }}</code></td>
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
                                <td colspan="5" style="text-align:center;color:var(--n-400);padding:32px 14px;">
                                    <i class="fa-solid fa-inbox" style="display:block;font-size:22px;margin-bottom:8px;" aria-hidden="true"></i>
                                    No inventory products registered yet.
                                </td>
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
            {{-- Search & Filter Toolbar --}}
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="productSearchInput" placeholder="Search by name or SKU..." autocomplete="off">
                </div>
                <select class="filter-select" id="productCategoryFilter">
                    <option value="ALL">All Categories</option>
                    @foreach ($products->pluck('category')->unique() as $cat)
                        <option value="{{ strtoupper($cat) }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="table-responsive">
                <table class="simple-table" id="productsTable">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Reorder Point</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="product-row"
                                data-name="{{ $product->name }}"
                                data-sku="{{ $product->sku }}"
                                data-category="{{ strtoupper($product->category) }}">
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
                                <td>
                                    <div class="actions-cell" style="justify-content: flex-end;">
                                        <button type="button" class="btn-sm btn-outline"
                                            onclick="openEditProductModal({{ json_encode([
                                                'id'            => $product->id,
                                                'name'          => $product->name,
                                                'sku'           => $product->sku,
                                                'category'      => $product->category,
                                                'supplier_id'   => $product->supplier_id,
                                                'location'      => $product->location,
                                                'quantity'      => $product->quantity,
                                                'reorder_point' => $product->reorder_point,
                                                'price'         => $product->price,
                                            ]) }})">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('products.destroy', $product->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete {{ addslashes($product->name) }} ({{ $product->sku }})?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-danger-sm">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: #64748b;">No products found in the catalog.</td>
                            </tr>
                        @endforelse
                        <tr id="productsEmptyFilterRow" style="display: none;">
                            <td colspan="8" style="text-align: center; color: #64748b; padding: 20px;">
                                <i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i> No products match your filter criteria.
                            </td>
                        </tr>
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
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <div style="font-size: 11.5px; color: var(--n-400); margin-top: 2px;">
                                        <i class="fa-solid fa-truck" style="font-size: 10px;"></i> {{ $product->supplier->name ?? 'No Supplier Assigned' }}
                                    </div>
                                </td>
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
                                            <button class="btn btn-primary" type="submit" style="padding: 4px 10px; font-size: 12px;"
                                                title="Reorder from {{ $product->supplier->name ?? 'Vendor' }}">
                                                <i class="fa-solid fa-rotate" style="margin-right: 3px;"></i> Reorder
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
            {{-- Quick Barcode / SKU Scanner input --}}
            <div style="margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                <label for="stockInBarcodeScan" style="font-size: 13px; color: #475569; margin: 0;">
                    <i class="fa-solid fa-barcode"></i> SKU / Barcode Scan:
                </label>
                <input type="text" id="stockInBarcodeScan" placeholder="Type/scan SKU & press Enter..."
                    style="max-width: 260px; padding: 6px 10px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 4px;"
                    onkeydown="if(event.key === 'Enter') { event.preventDefault(); const s = document.getElementById('stockInSku'); for(let i=0; i<s.options.length; i++) { if(s.options[i].value.toLowerCase() === this.value.trim().toLowerCase()) { s.selectedIndex = i; break; } } }">
            </div>

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
            {{-- Quick Barcode / SKU Scanner input --}}
            <div style="margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                <label for="stockOutBarcodeScan" style="font-size: 13px; color: #475569; margin: 0;">
                    <i class="fa-solid fa-barcode"></i> SKU / Barcode Scan:
                </label>
                <input type="text" id="stockOutBarcodeScan" placeholder="Type/scan SKU & press Enter..."
                    style="max-width: 260px; padding: 6px 10px; font-size: 13px; border: 1px solid #cbd5e1; border-radius: 4px;"
                    onkeydown="if(event.key === 'Enter') { event.preventDefault(); const s = document.getElementById('stockOutSku'); for(let i=0; i<s.options.length; i++) { if(s.options[i].value.toLowerCase() === this.value.trim().toLowerCase()) { s.selectedIndex = i; break; } } }">
            </div>

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
                            <th>Official Supplier</th>
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
                                    @if ($low->supplier)
                                        <span class="role-badge" style="display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-truck" aria-hidden="true"></i>
                                            <strong>{{ $low->supplier->name }}</strong>
                                        </span>
                                    @else
                                        <span style="color: var(--n-400); font-style: italic;">No vendor assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('stock.reorder', $low->id) }}" style="display: inline;">
                                        @csrf
                                        <button class="btn btn-primary" type="submit" style="padding: 4px 10px; font-size: 12px;"
                                            title="Reorder +{{ max($low->reorder_point * 2, 20) }} pcs from {{ $low->supplier->name ?? 'Supplier' }}">
                                            <i class="fa-solid fa-rotate" style="margin-right: 4px;"></i> Reorder (+{{ max($low->reorder_point * 2, 20) }} pcs)
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #16a34a; padding: 20px;">
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
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prodSupplier">Supplier / Vendor</label>
                        <select name="supplier_id" id="prodSupplier">
                            <option value="">-- No Supplier Assigned --</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->supplied_items }})
                                </option>
                            @endforeach
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
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
            <div class="page-header-text">
                <h1>Suppliers</h1>
                <p>Vendor contacts, partner directories, and supplied merchandise.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openAddSupplierModal()">
                <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Add Supplier
            </button>
        </div>
        <div class="card">
            <div class="table-responsive">
                <table class="simple-table" id="suppliersTable">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Contact Email</th>
                            <th>Phone</th>
                            <th>Supplied Items</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $sup)
                            <tr>
                                <td><strong>{{ $sup->name }}</strong></td>
                                <td><a href="mailto:{{ $sup->email }}" style="color: var(--brand-600); text-decoration: none;">{{ $sup->email }}</a></td>
                                <td>{{ $sup->phone }}</td>
                                <td>{{ $sup->supplied_items }}</td>
                                <td>
                                    <div class="actions-cell" style="justify-content: flex-end;">
                                        <button type="button" class="btn-sm btn-outline"
                                            onclick="openEditSupplierModal({{ json_encode([
                                                'id' => $sup->id,
                                                'name' => $sup->name,
                                                'email' => $sup->email,
                                                'phone' => $sup->phone,
                                                'supplied_items' => $sup->supplied_items,
                                            ]) }})">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('suppliers.destroy', $sup->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete supplier {{ addslashes($sup->name) }}?');"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-danger-sm">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--n-400); padding: 32px 14px;">
                                    <i class="fa-solid fa-truck" style="display: block; font-size: 24px; margin-bottom: 8px;" aria-hidden="true"></i>
                                    No suppliers registered yet. Click &ldquo;Add Supplier&rdquo; above to add your first vendor.
                                </td>
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
                <div>
                    <h3 style="font-size: 15px; color: #0f172a; margin: 0;">Stock Movement ({{ $currentYear }})</h3>
                    <p style="font-size: 12.5px; color: var(--n-500); margin: 3px 0 0;">Real database stock inbound & outbound movements by month.</p>
                </div>
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
                    @foreach ($monthlyMovements as $m)
                        @php
                            $inHeight = $maxMovementQty > 0 && $m['in'] > 0 ? max(8, round(($m['in'] / $maxMovementQty) * 100)) : 0;
                            $outHeight = $maxMovementQty > 0 && $m['out'] > 0 ? max(8, round(($m['out'] / $maxMovementQty) * 100)) : 0;
                        @endphp
                        <div class="chart-col">
                            <div class="bars-group">
                                <div class="chart-bar in" style="height: {{ $inHeight }}%;" title="{{ $m['month'] }}: Stock In {{ number_format($m['in']) }} pcs"></div>
                                <div class="chart-bar out" style="height: {{ $outHeight }}%;" title="{{ $m['month'] }}: Stock Out {{ number_format($m['out']) }} pcs"></div>
                            </div>
                            <span class="col-label">{{ $m['month'] }}</span>
                        </div>
                    @endforeach
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
                <div class="form-grid" style="max-width: 680px; margin-bottom: 18px;">
                    <div class="form-group">
                        <label for="settingAdminName">Administrator Name</label>
                        <input type="text" name="admin_name" id="settingAdminName" value="{{ session('admin_name', 'Administrator') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="settingAdminEmail">Administrator Email</label>
                        <input type="email" name="admin_email" id="settingAdminEmail" value="{{ session('admin_email', 'admin@inventory.local') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="currencySelect">Default Currency</label>
                        <select name="currency" id="currencySelect" required>
                            <option value="USD ($)" {{ $currency === 'USD ($)' ? 'selected' : '' }}>USD ($)</option>
                            <option value="EUR (€)" {{ $currency === 'EUR (€)' ? 'selected' : '' }}>EUR (€)</option>
                            <option value="GBP (£)" {{ $currency === 'GBP (£)' ? 'selected' : '' }}>GBP (£)</option>
                            <option value="PHP (₱)" {{ $currency === 'PHP (₱)' ? 'selected' : '' }}>PHP (₱)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lowStockThreshold">Default Low Stock Alert Threshold</label>
                        <input type="number" name="low_stock_threshold" id="lowStockThreshold" value="{{ $lowStockThreshold }}" min="1" max="1000" required>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 6px;"></i> Save System Settings
                </button>
            </form>
        </div>
    </section>

    {{-- Edit Product Modal Dialog --}}
    <div class="modal" id="editProductModal" role="dialog" aria-modal="true" aria-labelledby="editProductModalTitle">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="editProductModalTitle">
                    <i class="fa-solid fa-pen-to-square" style="color: #2563eb; margin-right: 6px;"></i> Edit Product
                </h3>
                <button type="button" class="modal-close" onclick="closeEditProductModal()" aria-label="Close dialog">&times;</button>
            </div>
            <form id="editProductForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="editProdName">Product Name</label>
                            <input type="text" name="name" id="editProdName" required>
                        </div>
                        <div class="form-group">
                            <label for="editProdSku">SKU Code</label>
                            <input type="text" name="sku" id="editProdSku" required>
                        </div>
                        <div class="form-group">
                            <label for="editProdCategory">Category</label>
                            <select name="category" id="editProdCategory" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProdSupplier">Supplier / Vendor</label>
                            <select name="supplier_id" id="editProdSupplier">
                                <option value="">-- No Supplier Assigned --</option>
                                @foreach ($suppliers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProdLocation">Warehouse Location</label>
                            <input type="text" name="location" id="editProdLocation" placeholder="e.g. Aisle 3, Bin B">
                        </div>
                        <div class="form-group">
                            <label for="editProdQty">Quantity (pcs)</label>
                            <input type="number" name="quantity" id="editProdQty" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="editProdPrice">Unit Price ($)</label>
                            <input type="number" name="price" id="editProdPrice" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label for="editProdReorderPoint">Reorder Point</label>
                            <input type="number" name="reorder_point" id="editProdReorderPoint" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeEditProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check" style="margin-right: 6px;"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Supplier Modal Dialog --}}
    <div class="modal" id="addSupplierModal" role="dialog" aria-modal="true" aria-labelledby="addSupplierModalTitle">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="addSupplierModalTitle">
                    <i class="fa-solid fa-truck" style="color: #2563eb; margin-right: 6px;"></i> Register New Supplier
                </h3>
                <button type="button" class="modal-close" onclick="closeAddSupplierModal()" aria-label="Close dialog">&times;</button>
            </div>
            <form method="POST" action="{{ route('suppliers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="addSupplierName">Supplier / Vendor Name</label>
                            <input type="text" name="name" id="addSupplierName" placeholder="e.g. Nexus Logistics Corp." required>
                        </div>
                        <div class="form-group">
                            <label for="addSupplierEmail">Contact Email</label>
                            <input type="email" name="email" id="addSupplierEmail" placeholder="vendor@nexus.com" required>
                        </div>
                        <div class="form-group">
                            <label for="addSupplierPhone">Phone Number</label>
                            <input type="text" name="phone" id="addSupplierPhone" placeholder="+1 (555) 012-3456" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="addSupplierItems">Supplied Items / Product Lines</label>
                            <input type="text" name="supplied_items" id="addSupplierItems" placeholder="e.g. Cables, Adapters, Keyboards" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeAddSupplierModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check" style="margin-right: 6px;"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Supplier Modal Dialog --}}
    <div class="modal" id="editSupplierModal" role="dialog" aria-modal="true" aria-labelledby="editSupplierModalTitle">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="editSupplierModalTitle">
                    <i class="fa-solid fa-pen-to-square" style="color: #2563eb; margin-right: 6px;"></i> Edit Supplier Details
                </h3>
                <button type="button" class="modal-close" onclick="closeEditSupplierModal()" aria-label="Close dialog">&times;</button>
            </div>
            <form id="editSupplierForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid">
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="editSupplierName">Supplier Name</label>
                            <input type="text" name="name" id="editSupplierName" required>
                        </div>
                        <div class="form-group">
                            <label for="editSupplierEmail">Contact Email</label>
                            <input type="email" name="email" id="editSupplierEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="editSupplierPhone">Phone Number</label>
                            <input type="text" name="phone" id="editSupplierPhone" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="editSupplierItems">Supplied Items</label>
                            <input type="text" name="supplied_items" id="editSupplierItems" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeEditSupplierModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check" style="margin-right: 6px;"></i> Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection