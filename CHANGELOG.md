# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Dedicated PSR-12 RESTful controllers (`InventoryController`, `ProductController`, `StockController`, `SupplierController`, `ReportController`, `SettingController`).
- Validated Form Requests (`StoreProductRequest`, `RecordStockInRequest`, `RecordStockOutRequest`) enforcing SKU uniqueness and positive numeric quantities.
- Eloquent models (`Product`, `StockMovement`, `Supplier`) with relationships and status accessors.
- Database migrations and `DatabaseSeeder` populating the SQLite catalog with default products, suppliers, and transaction history.
- Modular Blade layout architecture (`layouts/app.blade.php`) and component partials (`topbar`, `sidebar`, `mobile-tabs`, `alerts`).
- Modular JavaScript architecture partitioned into manageable ES modules (`navigation.js`, `sidebar.js`, `mega-menu.js`, `events.js`) with complete JSDoc annotations.
- Full Product CRUD & Management: Edit product modal dialog, validated product deletion with confirmation, and fast SKU barcode lookup API (`/products/lookup/{sku}`).
- Validated Form Request `UpdateProductRequest` enforcing unique SKU rules while ignoring the current product ID.
- Real-time catalog search and category filtering via dedicated `product-manager.js` module.
- Quick SKU / barcode scan inputs for Stock In and Stock Out warehouse forms.
- Full PHPUnit feature test suite (`InventoryControllerTest`, `ProductControllerTest`, `StockControllerTest`, `SettingControllerTest`) with 21 tests and 61 assertions passing.

### Changed
- Refactored `routes/web.php` from static closures to 17 named RESTful controller endpoints.
- Modularized `public/js/script.js` into modular ES components under `public/js/modules/` and `resources/js/modules/`.
- Consolidated application architecture by moving all inventory views, static assets, scripts, and dependencies from nested `My_Inventory` directory into the root Laravel application.
- Removed duplicate default Laravel welcome view and cleaned up redundant files.
