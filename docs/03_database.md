# 03 — Skema Database

## Overview

Sistem menggunakan **MySQL** sebagai database utama untuk semua keperluan:
- Data aplikasi
- Session (`sessions` table)
- Cache (`cache` table)
- Queue (`jobs` table)
- Activity log (`activity_log` table)

**Tipe Primary Key**: ULID (26 karakter, sortable secara kronologis) — kecuali tabel referensi wilayah yang menggunakan integer auto-increment.

---

## Daftar Tabel

### Tabel Sistem (Laravel Core)

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Akun pengguna sistem |
| `sessions` | Session aktif pengguna |
| `cache` | Cache data |
| `jobs` | Antrian background jobs |
| `activity_log` | Log aktivitas pengguna (spatie/activitylog) |

### Tabel Permission (Spatie)

| Tabel | Deskripsi |
|-------|-----------|
| `permissions` | Daftar hak akses |
| `roles` | Daftar peran pengguna |
| `role_has_permissions` | Mapping role ke permission |
| `model_has_roles` | Mapping model (User) ke role |
| `model_has_permissions` | Mapping model ke permission |

---

## Tabel Domain Bisnis

### 1. Pelanggan & Pesanan

```
customers
├── id (ULID, PK)
├── name (string)
├── phone (string)
├── address (text)
├── location (string) — koordinat/link maps
├── vilage_id (FK → ref_vilages.id)
├── gender (string, nullable)
├── created_by, updated_by, deleted_by
└── deleted_at (soft delete)

orders
├── id (ULID, PK)
├── customer_id (FK → customers.id)
├── order_ticket (string, unique) — format: LL-YYMMDDHHMM-XXX
├── estimate_price (decimal)
├── delivery_date (datetime)
├── event_date (datetime)
├── event_time (datetime)
├── total_guest (int)
├── total_invite (int)
├── status (enum: pending|approved|purchased|cancelled)
├── desc (text) — catatan umum
├── desc_extra (text) — catatan tambahan
├── event_type (string) — comma-separated: "pernikahan,sunatan"
├── package_type (string) — comma-separated
├── venue (string)
├── dp (decimal) — uang muka
├── created_by, updated_by, deleted_by
└── deleted_at (soft delete)

order_items
├── id (ULID, PK)
├── order_id (FK → orders.id)
├── menus_catering_id (FK → menus_catering.id)
├── custom_menu (string, nullable) — menu khusus tanpa referensi
├── quantity (decimal)
├── price (decimal)
├── notes (text)
└── timestamps

rincian_biaya
├── id (ULID, PK)
├── order_id (FK → orders.id)
├── name (string) — nama komponen biaya
├── quantity (int)
├── price (decimal)
└── timestamps
```

### 2. Menu Catering

```
category_menus_catering
├── id (ULID, PK)
├── name (string)
├── is_quantity (boolean) — apakah dihitung per porsi
├── seq (int, nullable) — urutan tampil
└── timestamps + blameable

menus_catering
├── id (ULID, PK)
├── name (string)
├── desc (text, nullable)
├── selling_price (decimal)
├── porsi_standard (decimal) — porsi untuk harga selling_price
├── category_menus_catering_id (FK)
├── is_active (boolean)
└── timestamps + blameable + soft delete

menus_catering_tumb
├── id (ULID, PK)
├── menus_catering_id (FK)
├── filename (string)
├── path (string)
├── disk (string)
└── timestamps

menus_catering_ingredients (Resep)
├── id (ULID, PK)
├── menus_catering_id (FK)
├── ingredient_id (FK → ingredients.id, nullable)
├── ingredient_label (string, nullable) — label teks bebas
├── quantity (decimal, nullable)
└── timestamps

packet_catering (Paket bundling)
├── id (ULID, PK)
├── name (string)
└── timestamps + blameable

packet_menus_catering (pivot)
├── id (ULID, PK)
├── packet_catering_id (FK)
├── menus_catering_id (FK)
└── timestamps
```

### 3. Bahan Baku & Supplier

```
ingredients
├── id (ULID, PK)
├── name (string)
├── unit (string) — satuan pembelian: "kg", "liter", "pcs"
├── satuan (string) — satuan kecil: "gram", "ml"
├── default_price (decimal)
└── timestamps + blameable + soft delete

suppliers
├── id (ULID, PK)
├── name (string)
├── phone (string, nullable)
├── address (text, nullable)
├── penanggung_jawab (string, nullable)
└── timestamps + blameable + soft delete

ingredients_suppliers (Bahan baku default supplier)
├── id (ULID, PK)
├── ingredient_id (FK)
└── supplier_id (FK)
```

### 4. Pembelian

```
purchases
├── id (ULID, PK)
├── order_id (FK → orders.id)
├── user_id (FK → users.id)
├── purchase_date (date)
└── timestamps + blameable

purchases_items
├── id (ULID, PK)
├── purchase_id (FK → purchases.id)
├── ingredient_id (FK → ingredients.id)
├── supplier_id (FK → suppliers.id, nullable)
├── quantity (decimal)
├── price (decimal)
└── timestamps + blameable
```

### 5. Cost Estimation

```
cost_estimations
├── id (ULID, PK)
├── order_id (FK → orders.id)
├── estimated_cost (decimal)
├── estimated_selling_price (decimal)
├── estimated_margin (decimal)
├── desc (text)
├── verified_by (FK → users.id)
└── cost_structure_id (FK → cost_structures.id, nullable)

cost_estimation_detail
├── id (ULID, PK)
├── cost_estimation_id (FK)
├── name (string) — nama komponen biaya
├── fixed (boolean) — apakah biaya tetap
├── kategori (string) — kategori biaya
├── prosentase (decimal)
├── prosentase_price (decimal, nullable)
├── fixed_price (decimal, nullable)
└── fixed_qty (int, nullable)

cost_structures (Template struktur biaya)
├── id (ULID, PK)
├── name (string)
└── timestamps + blameable

cost_structure_detail
├── id (ULID, PK)
├── cost_structure_id (FK)
├── name (string)
├── fixed (boolean)
├── kategori (string)
├── prosentase (decimal)
└── timestamps
```

### 6. Karyawan

```
employes
├── id (ULID, PK)
├── name (string)
├── phone (string)
├── address (text)
├── location (string, nullable)
├── gender (string)
├── national_id (string) — NIK
├── status (string) — tetap/kontrak/magang
├── work_since (date)
├── division (string)
├── birth_place_date (string)
├── height_cm (int, nullable)
├── weight_kg (int, nullable)
├── religion (string)
├── user_id (FK → users.id, nullable) — akun login karyawan
└── timestamps + blameable + soft delete

employee_educations
├── id (ULID, PK)
├── employee_id (FK → employes.id)
├── education_level (string)
├── school_name (string)
├── city (string)
├── major (string)
├── year_start (year)
└── year_graduated (year)

employee_families
├── id (ULID, PK)
├── employee_id (FK)
├── name (string)
├── relation (string)
├── birth_place_date (string)
├── gender (string)
└── education (string)

employee_emergencies
├── id (ULID, PK)
├── employee_id (FK)
├── name (string)
├── relation (string)
├── address (text)
└── phone (string)

employee_contracts
├── id (ULID, PK)
├── employee_id (FK)
├── contract_end (date)
└── interview_result (text)
```

### 7. Referensi Wilayah

```
ref_provinces  ← id integer, name
ref_cities     ← id integer, province_id, name
ref_districts  ← id integer, city_id, name
ref_vilages    ← id integer, district_id, name
```

Digunakan untuk alamat pelanggan dengan cascading dropdown.

### 8. Tabel Temporary (Import)

```
import_berkas
├── id (ULID, PK)
├── filename (string)
├── path (string)
├── disk (string)
├── status (string)
├── error (text, nullable)
└── timestamps + blameable

temp_recipe_menu (Staging import resep)
├── id (ULID, PK)
├── batch_id (string) — grouping per import
├── menus_catering_id (FK, nullable)
└── data JSON fields

temp_ingredients_menu (Staging bahan baku import)
├── id (ULID, PK)
├── batch_id (string)
└── data JSON fields
```

---

## Diagram Relasi Utama

```
customers ──────────── orders
                         │
           ┌─────────────┼─────────────────┐
           │             │                 │
      order_items  cost_estimations    purchases
           │             │                 │
     menus_catering  cost_struct_detail purchases_items
           │                                │
   category_menus              ingredients + suppliers
   menus_catering_ingredients
```

---

## Indeks Penting

| Tabel | Kolom Diindeks |
|-------|----------------|
| `orders` | `customer_id`, `status`, `event_date`, `created_at` |
| `order_items` | `order_id`, `menus_catering_id` |
| `menus_catering` | `category_menus_catering_id`, `is_active` |
| `purchases_items` | `purchase_id`, `ingredient_id`, `supplier_id` |

---

## Soft Delete

Model yang menggunakan Soft Delete (`deleted_at`):
- `customers`
- `orders`
- `menus_catering`
- `ingredients`
- `suppliers`
- `employes`

Kolom `deleted_by` diisi otomatis oleh trait Blameable sebelum soft delete.
