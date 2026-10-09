# System Data Flow & Logic Processes (Daloy ng Datos)

Ang dokumentong ito ay nagpapaliwanag sa sunod-sunod na daloy ng impormasyon (logic workflow) sa buong Inventory System. Gumagamit ito ng visual Mermaid diagrams at simpleng Taglish para madaling masundan ng mga nagsisimula pa lamang mag-aral ng web development.

---

## 1. Pangkalahatang Request Lifecycle (Web & API Flow)

Paano naglalakbay ang isang request mula sa browser ng user hanggang sa makabalik ang sagot:

```mermaid
sequenceDiagram
    autonumber
    actor User as User (Browser)
    participant Route as routes/web.php
    participant FormReq as Form Request (Validation)
    participant Controller as Controller (Business Logic)
    participant DB as SQL Database (Eloquent)
    participant View as Blade Template / JSON

    User->>Route: Nag-submit ng form o nag-click ng link (HTTP GET/POST/PUT/DELETE)
    Route->>FormReq: I-check kung valid ang inputs (hal. unique SKU, valid email)
    alt Validation Failed (May Mali sa Input)
        FormReq-->>User: Ibalik agad sa form na may red error alert banners
    else Validation Passed (Lahat Tama)
        FormReq->>Controller: Ipasa ang malinis at ligtas na validated data
        Controller->>DB: Magbasa o mag-save sa SQL database via Eloquent Model
        DB-->>Controller: Matagumpay na naitala ang record
        Controller-->>View: I-render ang updated view kasama ang success flash message
        View-->>User: Makikita ng user ang bagong data at auto-hiding toast alert!
    end
```

---

## 2. Stock In Flow (Papasok na Stock mula sa Supplier)

Kapag may dumating na bagong delivery o package mula sa supplier:

```mermaid
flowchart TD
    Start([User nag-submit ng Stock In form]) --> SkuCheck{Umiiral ba ang SKU?}
    SkuCheck -- Wala / Invalid --> Error404[I-abort: 404 Product Not Found]
    SkuCheck -- Mayroon --> ValQty{Positive number ba ang quantity?}
    ValQty -- Hindi (<= 0) --> ValError[Ibalik na may validation error]
    ValQty -- Oo (> 0) --> TxStart[Simulan ang DB::transaction]

    TxStart --> Incr[Dagdagan ang Product quantity sa database]
    Incr --> CreateLog[Gumawa ng bagong StockMovement record type='in']
    CreateLog --> Commit[I-commit ang Transaction sa SQL]
    Commit --> SuccessFlash[Gumawa ng Success Alert: '+X pcs for item']
    SuccessFlash --> Redirect[I-redirect pabalik sa Stock tab]
```

---

## 3. Stock Out Flow (Pag-dispatch ng Stock na may Safety Check)

Kapag naglabas ng items para sa delivery, sales order, o bawas sa bodega:

```mermaid
flowchart TD
    Start([User nag-submit ng Stock Out form]) --> FindProd[Hanapin ang produkto gamit ang SKU]
    FindProd --> StockCheck{Sapat ba ang stock?<br/>Available >= Requested?}
    StockCheck -- Hindi Sapat (Insufficient) --> InsufficientAlert[Ibalik na may Error Alert:<br/>'Kulang ang stock para sa item!']
    StockCheck -- Sapat (May supply pa) --> TxBegin[Simulan ang DB::transaction]
    TxBegin --> Decr[Bawasan ang Product quantity sa database]
    Decr --> LogOut[Magtala ng StockMovement type='out']
    LogOut --> TxDone[I-commit ang Transaction sa SQL]
    TxDone --> FlashOut[Ipakita ang green success alert: '-X pcs recorded']
    FlashOut --> Done([Matagumpay na natapos])
```

---

## 4. Reorder Flow (Kaninong Supplier Bibili?)

Kapag ang stock ng isang produkto ay bumaba sa o pantay sa kanyang safety `reorder_point`, magiging aktibo ang Reorder button:

```mermaid
flowchart TD
    A[Low Stock Item natukoy sa database] --> B[Pinindot ng User ang 'Reorder' button]
    B --> C[Alamin ang dami: max reorder_point * 2 o 20 pcs]
    C --> D{May naka-link bang Official Supplier?}
    D -- May Supplier --> E[Kunin ang pangalan ng Supplier hal. 'Apex Electronics Co.']
    D -- Walang Supplier --> F[Gamitin ang fallback: 'General Warehouse Supplier']
    E --> G[Increment product quantity]
    F --> G
    G --> H[Gumawa ng StockMovement type='in'<br/>notes='Reordered from supplier: [Pangalan]']
    H --> I[I-flash ang success message sa UI:<br/>'Reordered +X pcs for [Item] from supplier: [Pangalan]']
    I --> J[Awtomatikong magre-refresh ang live table at dynamic charts]
```

---

## 5. Supplier Management Flow (Add, Edit, Delete)

```mermaid
flowchart TD
    A[User pinindot ang 'Add Supplier'] --> B[Lilitaw ang Add Supplier Modal]
    B --> C[I-type ang Name, Email, Phone, at Supplied Items]
    C --> D[I-submit ang form sa POST /suppliers]
    D --> E{Validation Check:<br/>Unique ba ang name? Valid email?}
    E -- May duplicate o kulang --> F[Ibalik ang error banner sa screen]
    E -- Lahat tama --> G[Supplier::create na-save sa SQL database]
    G --> H[Ipakita ang success banner at i-close ang modal]
    H --> I[Naka-display na ang bagong supplier sa live table]
    I --> J[Pwedeng piliin bilang official supplier sa Add/Edit Product!]
```

---

## 6. Real-time Notifications Aggregation Flow

Paano gumagana ang notification bell sa header bar nang hindi bumibigat ang website:

```mermaid
sequenceDiagram
    autonumber
    participant Browser as Browser (topbar.js)
    participant API as /api/notifications
    participant DB as SQL Database

    Browser->>API: GET request tuwing 60 segundo (Background Polling)
    API->>DB: Query 1: Kunin ang mga items kung saan quantity <= reorder_point
    API->>DB: Query 2: Kunin ang huling 6 transactions sa stock_movements
    DB-->>API: Ibalik ang combined array ng alerts at movements
    API-->>Browser: JSON payload { count, unread, notifications: [...] }
    Browser->>Browser: I-update ang pulang badge sa notification bell
    alt Kapag pinindot ng user ang Bell
        Browser->>Browser: I-render ang dynamic list items sa right-aligned panel nang walang page refresh!
    end
```
