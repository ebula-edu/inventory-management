# Application Architecture

## Overview
The Inventory System is built on Laravel 12 as a single monolithic application served directly from the root repository directory.

## Directory Structure
- `app/`: Framework models, controllers, and service providers.
- `database/`: SQLite database file (`database/database.sqlite`), migration schemas, and seeders.
- `public/`: Web server document root. Contains:
  - `css/style.css`: Primary application styling.
  - `js/script.js`: Interactive navigation, modal, and page-switching behavior.
  - `index.php`: Laravel front controller.
- `resources/`:
  - `views/inventory.blade.php`: Core unified inventory interface covering Dashboard, Inventory Overview, Products List, Stock Management, In/Out actions, and Settings.
  - `css/app.css`: Source CSS.
  - `js/app.js`: Source JavaScript.
- `routes/web.php`: Web routes, mapping `/` to `inventory.blade.php`.
- `tests/`: Feature and Unit tests (`php artisan test`).
