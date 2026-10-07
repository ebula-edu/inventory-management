# Application Architecture

## Overview
The Inventory System is built on Laravel 12 as a modular monolithic web application served directly from the root repository directory. It features an idiomatic MVC architecture with full Controller-to-Route mappings, Form Request validations, Eloquent ORM models, and modular JavaScript.

## Directory Structure
- `app/Http/Controllers/`: Dedicated PSR-12 RESTful controllers:
  - `InventoryController.php`: Coordinates dashboard metrics, overview datasets, and view dispatch.
  - `ProductController.php`: Manages product catalog listings and validated store actions.
  - `StockController.php`: Handles stock-in receipts, stock-out dispatches, negative stock prevention, and low-stock reorders.
  - `SupplierController.php`: Renders supplier directory.
  - `ReportController.php`: Handles inventory turnover and category analytics.
  - `SettingController.php`: Handles system preferences and session settings.
- `app/Http/Requests/`: Validated form requests:
  - `StoreProductRequest.php`: Validates SKU uniqueness, pricing, and required product metadata.
  - `RecordStockInRequest.php`: Validates product existence and positive inbound quantity.
  - `RecordStockOutRequest.php`: Validates product existence and positive outbound quantity.
- `app/Models/`: Eloquent ORM models with PHPDoc annotations and relationships:
  - `Product.php`: Product catalog entity with stock movements relationship and status accessors.
  - `StockMovement.php`: Inbound/outbound ledger entries linked to products.
  - `Supplier.php`: External vendor information.
- `database/`:
  - `migrations/`: Schema definitions for `products`, `suppliers`, and `stock_movements`.
  - `database.sqlite`: Fallback / testing SQLite database store.
  - XAMPP MySQL: Production/development relational database (`inventory_system` at `127.0.0.1:3306`).
- `public/`:
  - `css/style.css`: Primary application styling with alert banners and responsive tables.
  - `js/script.js`: Main ES module orchestrator.
  - `js/modules/`: Manageable client-side ES modules:
    - `navigation.js`: Page tab transitions and active navigation indicator syncing.
    - `sidebar.js`: Mobile drawer drawer open/close and accordion state.
    - `mega-menu.js`: Desktop mega-menu dropdown management.
    - `events.js`: Global outside-click and Escape key listeners.
- `resources/`:
  - `views/layouts/app.blade.php`: Master Blade layout with alerts, navigation, and script inclusion.
  - `views/partials/`: Modular view partials (`topbar.blade.php`, `sidebar.blade.php`, `mobile-tabs.blade.php`, `alerts.blade.php`).
  - `views/inventory.blade.php`: Main view integrating live Eloquent models with CSRF-protected forms.
  - `js/modules/`: Development ES modules mirrored for Vite asset compilation.
  - `js/app.js`: Main Vite JavaScript entry point.
- `routes/web.php`: Named RESTful routes mapping cleanly to controller actions.
- `tests/Feature/`: Comprehensive PHPUnit feature test suite covering all routes, validation rules, and stock transactions.
