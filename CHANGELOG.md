# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.1] — 2026-10-08

### Fixed
- **Mobile Navigation Drawer Event Conflict**:
  - Resolved event bubbling issue where document outside-click listener immediately closed the drawer upon clicking the hamburger button.
  - Added explicit exclusion check for `#hamburgerBtn` in `events.js` and `event.stopPropagation()` on the button trigger.
  - Added dedicated mobile close button (`&times;`) inside the sidebar header for ergonomic drawer dismissal.
- **Mobile Dropdown Panel Positioning ("fications" cutoff)**:
  - Fixed notification and profile panel CSS on mobile view (`@media (max-width: 768px)`): anchored panels relative to the viewport (`position: fixed; left: 12px; right: 12px;`) to eliminate horizontal displacement and ensure full readability.
- **Cross-Page Responsiveness**:
  - Full-width stacked search and filter toolbars on catalog and stock views for mobile screens.
  - Responsive single-column card grids on small screens (`<= 480px`).
  - Mobile bottom navigation tabs bar with touch-optimized hit areas and safe-area padding.
  - Responsive modal dialogs with scrollable body panels and wrapped action buttons.

---

## [1.3.0] — 2026-10-08

### Added
- **Product-to-Supplier Relational Mapping (`supplier_id`)**:
  - Database migration `2026_10_08_140000_add_supplier_id_to_products_table.php` establishing foreign key relation between `products` and `suppliers`.
  - Eloquent relationships: `Product::supplier()` (`belongsTo`) and `Supplier::products()` (`hasMany`).
  - Added official supplier dropdown selection in Add Product and Edit Product modals.
  - Low-stock alerts and Stock Out / In forms now display the registered official supplier.
  - One-click reorders in Low Stock Alerts now track and attribute the replenishment to the product's official supplier in both `stock_movements.notes` and the flash success notification.
- **Alert Toast Auto-Dismiss & Manual Close**:
  - Added interactive close button (`&times;`) to flash alert banners.
  - Automatic 4-second timer with smooth CSS opacity transition (`.alert-fade-out`).
- **Comprehensive Educational Documentation (Taglish & Beginner Friendly)**:
  - [`docs/beginner-guide.md`](docs/beginner-guide.md): File-by-file walkthrough explaining what each file is for, when it is called, how MVC interacts, and step-by-step resolution for permission errors.
  - [`docs/database.md`](docs/database.md): Complete relational SQL design, Mermaid ERD diagram, data dictionary, sample relational queries, and MySQL/SQLite portability instructions.
  - [`docs/data-flow.md`](docs/data-flow.md): 6 detailed Mermaid system flow diagrams for Stock In, Stock Out, Supplier Reorder, Polling, and CRUD actions.
  - [`docs/architecture.md`](docs/architecture.md): High-level system architecture and controller-to-view relationships.

### Fixed
- **CSS Linter Warnings**:
  - Resolved empty ruleset warning on `.page-header-text`.
  - Added standard `appearance: none;` alongside `-webkit-appearance: none;` in `.filter-select`.
- **Notification Panel Layout Overflow**:
  - Fixed `.dropdown-panel` alignment to anchor strictly to the right (`right: 0; left: auto; max-width: calc(100vw - 32px)`), completely preventing the page from shifting or creating horizontal scrollbars when opening the notification panel.

---

## [1.2.0] — 2026-10-08

### Added
- **Full Supplier CRUD**:
  - `SupplierController` endpoints (`store`, `update`, `destroy`) with strict validation rules (unique names, email format, phone, and supplied product lines).
  - Supplier feature test suite in `tests/Feature/SupplierControllerTest.php` covering creation, duplicate rejection, editing, and deletion.
  - Interactive "Add Supplier" and "Edit Supplier" accessible modal dialogs with form controls, Escape-key dismissal, and outside click handling.
  - Action buttons (`Edit`, `Delete`) on every supplier record in the suppliers directory.
  - Dedicated ES module `supplier-manager.js` in both `public/js/modules` and `resources/js/modules`.
- **Dynamic Real Data Reporting**:
  - Replaced hardcoded mockup bars in Reports & Analytics with dynamic monthly aggregation directly from the `StockMovement` database table for the current calendar year.
  - Real calculations for `Stock In` and `Stock Out` volumes, automated relative scaling against maximum monthly unit movements, and informative unit tooltips.
- **Dynamic Admin Profile & Settings**:
  - Support for configuring Administrator Display Name and Email in `SettingController` and Settings view.
  - Topbar admin profile avatar badge, modal title, contact email, and active inventory metrics are populated dynamically from live database models and session state.

### Changed
- Category selection in product creation and editing modals now dynamically reads existing categories from the database catalog.

---

## [1.1.0] — 2026-10-08

### Added
- **Permanent left sidebar navigation**: replaces the horizontal topbar nav with a fixed `248px` left panel visible on all desktop breakpoints.
- **Slim topbar header**: shows dynamic page title (updated via JS on navigation), notification bell button, and user avatar.
- **Design token system** in `style.css`: CSS custom properties for brand colours, neutrals, sidebar theming, surface colours, and transitions — enabling consistent theming across components.
- **Inter font** (Google Fonts) as the application typeface, replacing system-default stack.
- **`PAGE_TITLES` map** in `navigation.js`: human-readable titles per page ID, automatically updated in the topbar on every navigation event.
- **`updateTopbarTitle()`** function in `navigation.js` keeping the slim topbar title in sync.
- **`aria-expanded` management** on the sidebar accordion toggle and hamburger button for accessible state indication.

### Changed
- **Layout shell** (`layouts/app.blade.php`): restructured to `.app-shell` two-column flexbox — sidebar + `.main-column` (topbar + content).
- **`sidebar.blade.php`**: complete rewrite — now the primary navigation panel for all screen sizes, with full inventory accordion, icon-labelled links, brand area, and version footer.
- **`topbar.blade.php`**: simplified to a slim header bar (hamburger on mobile, page title, notification + user actions). All nav links removed.
- **`navigation.js`**: selectors updated for sidebar-only navigation, inventory accordion is automatically kept open when any sub-page is active.
- **`sidebar.js`**: updated `aria-expanded` tracking on hamburger and accordion toggle; overlay now targets `#overlay` (unified ID).
- **`events.js`**: mega-menu dismissal removed; only mobile sidebar click-outside and Escape-key dismissal remain.
- **`script.js`**: mega-menu imports and `window.toggleMegaMenu` / `window.closeMegaMenu` removed; `window.showPage` simplified to direct reference.
- **`app.js`** (Vite source): same cleanup as `script.js` above.
- **CSS** (`style.css`): complete rewrite — sidebar-first layout, design tokens, responsive two-column shell, mobile drawer, improved component styles throughout.

### Deprecated
- `public/js/modules/mega-menu.js` and `resources/js/modules/mega-menu.js`: no longer imported; retained for reference and safe to delete in a future cleanup pass.

### Removed
- Desktop horizontal navigation bar (`.desktop-nav`, `.nav-link`, `.mega-menu` and all related CSS classes).
- `toggleMegaMenu`, `closeMegaMenu` window globals (no longer needed).

---

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
