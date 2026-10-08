# 📦 Inventory System — Warehouse & Stock Management

A modern, fast, and responsive web-based inventory management system designed for store managers, warehouse staff, and business owners to track stock levels, monitor low stock alerts, manage product catalogs, and handle inbound/outbound stock operations reliably.

---

## 🌟 Key Features

- **📊 Dashboard & Live Analytics**: Real-time overview of total catalog items, daily stock-in movements, low-stock alerts, and dynamic monthly movement bar charts.
- **🏷️ Product Catalog**: Full product listing with category filtering, instant SKU search, modal-based item registration, and live editing.
- **🚚 Supplier Management**: Full vendor directory with contact details, supplied product lines, and interactive modal dialogs for adding and editing suppliers.
- **📥 Stock In & 📤 Stock Out**:
  - Inbound deliveries automatically increment stock counts and log audit records.
  - Outbound dispatches strictly enforce available inventory limits with real-time validation to prevent negative stock balances.
- **⚡ Official Supplier Reordering**: Low stock alerts identify each product's official vendor and support one-click restock reordering attributed directly to that supplier.
- **🔔 Live Notifications & Alerts**: Polled notification feed on the topbar and auto-dismissing flash toast messages (with manual close button).
- **📱 Responsive Layout**: Fixed desktop sidebar navigation with mobile-friendly drawer and touch-friendly controls.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+ · [Laravel 12](https://laravel.com)
- **Database**: Standard Relational SQL (SQLite for local zero-config development; MySQL / MariaDB for server deployments)
- **Frontend**: Blade Templates · Vanilla CSS3 (Design Tokens) · Modular ES6+ JavaScript
- **Asset Pipeline**: [Vite](https://vite.dev)
- **Testing**: PHPUnit 11 (Feature & Unit suites)

---

## 🚀 Quick Start Guide

### 1. Prerequisites
Ensure you have the following installed on your machine:
- PHP 8.2 or higher
- Composer 2.x
- Node.js (v18+) & npm

### 2. Installation Steps

1. **Clone the repository and enter the directory**:
   ```bash
   git clone <repository-url>
   cd inventory-system
   ```

2. **Install PHP and Node dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment File**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set Up the Database**:
   - **Option A: SQLite (Quickest, Zero Configuration)**
     ```bash
     touch database/database.sqlite
     # Set DB_CONNECTION=sqlite in your .env file
     php artisan migrate --seed
     ```
   - **Option B: MySQL / MariaDB**
     Configure your credentials in `.env`:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=inventory_system
     DB_USERNAME=root
     DB_PASSWORD=
     ```
     Then run the migrations:
     ```bash
     php artisan migrate --seed
     ```

5. **Build Frontend Assets**:
   ```bash
   npm run build
   ```

6. **Start the Development Server**:
   ```bash
   php artisan serve
   ```
   Open your browser and navigate to: **`http://127.0.0.1:8000`**

---

## 🧪 Automated Testing

The project includes automated feature and unit test coverage verifying catalog integrity, stock adjustments, negative balance protection, and supplier management.

Run the test suite:
```bash
php artisan test
```

---

## 📚 Project Documentation

Detailed guides and architecture references are available in the [`docs/`](docs/) directory:

- 📖 **[Beginner Study Guide](docs/beginner-guide.md)**: Taglish walkthrough explaining file-by-file purposes, when each file is called, and how MVC functions.
- 🗄️ **[Database Design & ERD](docs/database.md)**: Full relational SQL design, Mermaid ERD diagram, table structures, and sample queries.
- 🔄 **[System Data Flows](docs/data-flow.md)**: Visual sequence diagrams illustrating the lifecycle of Stock In, Stock Out, Supplier Reordering, and Notifications.
- 🏛️ **[Application Architecture](docs/architecture.md)**: Directory map and controller responsibilities.

---

## ⚙️ Configuration & Server Notes

- **Permission Notice**: When running server control tools or modifying core server `.ini` configuration files on your operating system, always launch the control application with **Administrator / Elevated Privileges** ("Run as administrator" on Windows or `sudo` on Linux) to prevent permission errors when saving settings.
- **Database Switching**: Switching between SQLite and MySQL only requires updating `DB_CONNECTION` in `.env` and running `php artisan migrate`.

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
