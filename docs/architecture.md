# Application Architecture (Arkitektura ng Sistema)

## 📌 Overview (Pangkalahatang Panimula)
Ang **Inventory System** ay binuo gamit ang **Laravel 12** bilang isang modular monolithic web application. Gumagamit ito ng idiomatic MVC (Model-View-Controller) architecture na may maayos na route mappings, strict Form Request validations, Eloquent ORM models para sa database, at modular vanilla JavaScript para sa responsive user interface.

---

## 🗂️ Directory Structure & File Map

### 1. Backend Controllers (`app/Http/Controllers/`)
Naglalaman ng PSR-12 RESTful controllers kung saan nakalagay ang business logic ng app:
- `InventoryController.php`: Central coordinator para sa dashboard statistics, monthly stock movement aggregations, at multi-tab rendering.
- `ProductController.php`: Namamahala sa product catalog, item creation, barcode lookup, at updates.
- `StockController.php`: Nagpapatakbo ng Stock In receiving, Stock Out dispatches, at supplier-aware automated reordering.
- `SupplierController.php`: Nagpapatakbo ng supplier directory, CRUD operations para sa vendor management.
- `ReportController.php`: Nagre-render ng monthly inventory turnover at category distribution analysis.
- `SettingController.php`: Namamahala sa system preferences, currency, low stock alert threshold, at administrator profile.
- `NotificationController.php`: Real-time JSON API endpoint para sa notification panel sa topbar.

### 2. Validation & Security (`app/Http/Requests/`)
Sinisiguradong malinis at ligtas ang lahat ng incoming data bago pumasok sa database:
- `StoreProductRequest.php`: Sinusuri ang SKU uniqueness, category, supplier ID, at positive quantity.
- `UpdateProductRequest.php`: Sinusuri ang product updates habang pinapanatiling unique ang SKU.
- `RecordStockInRequest.php`: Sinusuri ang SKU existence at inbound quantity.
- `RecordStockOutRequest.php`: Sinusuri ang outbound quantity at binabantayan ang negative stock risk.

### 3. Eloquent ORM Models (`app/Models/`)
- `Product.php`: Kumakatawan sa `products` table; may relationship na `belongsTo(Supplier::class)` at `hasMany(StockMovement::class)`.
- `Supplier.php`: Kumakatawan sa `suppliers` table; may relationship na `hasMany(Product::class)`.
- `StockMovement.php`: Kumakatawan sa `stock_movements` table; may relationship na `belongsTo(Product::class)`.

### 4. Database Migrations (`database/migrations/`)
- `create_products_table.php`: Master products table.
- `create_suppliers_table.php`: Vendor directory table.
- `create_stock_movements_table.php`: Inbound/outbound transaction ledger.
- `add_supplier_id_to_products_table.php`: Foreign key relationship na nagli-link ng bawat produkto sa official supplier nito.

### 5. Frontend & JavaScript Modules (`public/js/modules/`)
- `script.js`: Main application orchestrator na nag-i-initialize ng lahat ng sub-modules.
- `navigation.js`: Seamless client-side page switching nang walang full page reload.
- `sidebar.js`: Sidebar drawer open/close at inventory accordion toggling.
- `topbar.js`: Real-time notification feed polling at admin profile dropdown management.
- `supplier-manager.js`: Add at Edit Supplier modals na may keyboard at backdrop click support.
- `product-manager.js`: Real-time table search filter at Edit Product modal lifecycle.
- `events.js`: Flash alert auto-dismiss (4 seconds) at manual close button handlers.

### 6. Blade Templates & Layouts (`resources/views/`)
- `layouts/app.blade.php`: Master HTML5 shell na may design tokens, Inter font, at FontAwesome icons.
- `partials/sidebar.blade.php`: Permanent left sidebar navigation panel.
- `partials/topbar.blade.php`: Slim header bar na may dynamic title, notifications bell, at admin profile.
- `partials/alerts.blade.php`: Reusable flash notification partial na may auto-hide at close button.
- `partials/mobile-tabs.blade.php`: Bottom navigation bar para sa mga mobile screens.
- `inventory.blade.php`: Single-page workspace na naglalaman ng lahat ng application tabs at interactive modals.
