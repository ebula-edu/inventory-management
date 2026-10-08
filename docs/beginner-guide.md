# Gabay Para sa mga Nagsisimula (Beginner's Study Guide)

Maligayang pagdating sa **Inventory Management System**! Ang gabay na ito ay isinulat sa wikang **Taglish (Tagalog + English)** upang madaling maunawaan ng sinumang mag-aaral o baguhan sa web development at software engineering.

---

## 🧭 1. Paano Gumagana ang Laravel MVC Architecture?

Ang proyektong ito ay sumusunod sa **MVC (Model - View - Controller)** pattern. Isipin mo ito tulad ng isang maayos na restawran:

1. **Route (`routes/web.php`) — Ang Menu**:
   - Dito nakalista ang lahat ng mga URLs (hal. `/dashboard`, `/products`, `/suppliers`).
   - Sinasabi nito kung saang Kusinero (Controller) dadalhin ang order ng customer.

2. **Controller (`app/Http/Controllers/`) — Ang Kusinero**:
   - Tumatanggap ng request mula sa user.
   - Siya ang nagpapasya kung anong data ang kukunin, anong validation ang gagawin, at kung paano ito i-compute.

3. **Model (`app/Models/`) — Ang Tagapamahala ng Bodega (Database)**:
   - Kumakatawan sa mga SQL tables (`products`, `suppliers`, `stock_movements`).
   - Sa pamamagitan ng Eloquent ORM, madali tayong makakapag-save, update, at delete sa database nang hindi nagsusulat ng kumplikadong raw SQL string.

4. **View (`resources/views/`) — Ang Pinggan na Ihahain (User Interface)**:
   - Ang nakikita ng user sa browser.
   - Gumagamit ito ng Blade templates (`.blade.php`) na pinaghalong HTML, CSS, at lightweight PHP tags para maipakita ang dynamic na datos.

---

## 📁 2. File-by-File Overview: Ano ang gamit at kailan tinatawag?

### A. Mga Controllers (`app/Http/Controllers/`)

| File Name | Ano ang gamit nito? (File Purpose) | Kailan ito tinatawag? (When is it called?) |
|---|---|---|
| `InventoryController.php` | Central coordinator. Kinakalkula ang total items, stock-in today, low stock counts, at monthly movements. | Kapag binuksan ang main Dashboard (`/`) o Inventory Overview (`/inventory`). |
| `ProductController.php` | Catalog manager. Nagko-kontrol sa paggawa, pag-update, pagbura, at barcode search ng mga produkto. | Kapag nag-navigate sa Products tab, nag-save ng Add Product, o nag-scan ng SKU. |
| `StockController.php` | Warehouse operator. Nagre-record ng Stock In, Stock Out, at Reorder transactions. | Kapag may delivery na dumating (`/stock/in`), nag-dispatch (`/stock/out`), o nag-reorder sa supplier. |
| `SupplierController.php` | Vendor directory manager. Nag-aayos ng listahan ng mga suppliers, pagdagdag, at pag-edit. | Kapag binuksan ang Suppliers tab (`/suppliers`) o nag-save sa Add/Edit Supplier modal dialogs. |
| `ReportController.php` | Analytics renderer. Ipinapakita ang buwanang takbo ng stock movements at category share. | Kapag binuksan ang Reports tab (`/reports`). |
| `SettingController.php` | Preferences manager. Nag-iingat ng Admin Name, Email, Currency, at Low Stock Threshold sa session. | Kapag nagpalit ng settings sa Settings tab (`POST /settings`). |
| `NotificationController.php` | Real-time notification feed. Nagbabalik ng JSON list ng low stock alerts at recent transactions. | Tinatawag ng JavaScript (`fetch('/api/notifications')`) sa background tuwing 60 segundo. |

### B. Mga Eloquent Models (`app/Models/`)

| File Name | Talahanayan (SQL Table) | Paliwanag (Taglish Description) |
|---|---|---|
| `Product.php` | `products` | Naglalaman ng pangalan, SKU, category, presyo, at dami ng produkto. May relationship na `belongsTo(Supplier::class)` para malaman kung sino ang official supplier kapag nag-reorder! |
| `Supplier.php` | `suppliers` | Naglalaman ng pangalan ng vendor, email, telepono, at mga items na kanilang ibinibenta. May relationship na `hasMany(Product::class)`. |
| `StockMovement.php` | `stock_movements` | Ang logbook ng bodega. Bawat bawas o dagdag ng gamit ay may nakatalang produkto, dami, at dahilan/supplier. |

### C. Client-Side JavaScript Modules (`public/js/modules/`)

| File Name | Responsibilidad |
|---|---|
| `navigation.js` | Nagpapalit ng active tab (Dashboard, Products, Suppliers, etc.) nang walang full page reload. |
| `sidebar.js` | Nagbubukas at nagsasara ng navigation drawer sa mobile screen at nagko-control sa accordion menu. |
| `topbar.js` | Nagpapagana sa notification bell panel at profile dropdown. Awtomatikong nagfa-fetch ng bagong alerts. |
| `supplier-manager.js` | Nagbubukas at nagsasara ng Add at Edit Supplier modals, may suporta para sa Escape key at backdrop click. |
| `product-manager.js` | Real-time search filter sa table at Edit Product modal lifecycle. |
| `events.js` | Global listeners para sa alert auto-dismiss (4 seconds) at manual close button. |

---

## 🛠️ 3. Troubleshooting & Common Setup Tips

### ❓ Karaniwang Problema: Configuration File "Access is denied"
Kung may lalabas na permission warning kapag nagse-save ng server configuration o control panel file:
> `Error: Cannot create file "...config.ini". Access is denied`

**Bakit ito nangyayari?**
Karaniwang pinoprotektahan ng Operating System security (tulad ng Windows UAC o Linux directory permissions) ang root system drives laban sa mga proseso na walang administrator o write access.

**Paano ayusin (Resolution):**
1. Isara ang server control panel o console application.
2. Patakbuhin ito gamit ang Administrator / Elevated privileges ("Run as administrator" sa Windows o `sudo` sa Linux).
3. I-save muli ang configuration, at matagumpay na itong masusulat nang walang permission error.

---

## 🧪 4. Paano Patakbuhin at Subukan ang Proyekto

1. **Pumunta sa root directory ng project sa terminal**:
   ```bash
   cd /path/to/inventory-system
   ```

2. **Patakbuhin ang Automated Tests**:
   ```bash
   php artisan test
   ```
   *Dapat makita mo ang `PASS` sa lahat ng 26 tests.*

3. **Patakbuhin ang Local Development Server**:
   ```bash
   php artisan serve
   ```
   *Buksan ang browser at pumunta sa `http://127.0.0.1:8000`.*
