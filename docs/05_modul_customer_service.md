# 05 — Modul Customer Service

## Deskripsi

Modul **Customer Service** adalah inti dari sistem ini. Modul ini mengelola seluruh siklus pesanan mulai dari pelanggan memesan hingga laporan dikirim. CS (Customer Service) bertugas membuat, memperbarui, dan mengelola pesanan pelanggan.

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/CustomerServicesController.php` |
| API Controller | `app/Http/Controllers/Api/CustomerServicesApiController.php` |
| Repository | `app/Repository/CustomerServicesRepository.php` |
| Resource | `app/Http/Resources/CustomerServiceResource.php` |
| Models | `Orders`, `OrderItems`, `Customers`, `RincianBiaya` |

---

## Route

### Web (Blade Pages)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/customer_service` | `index()` | Halaman daftar pesanan |
| GET | `/customer_service/list_orders` | `list_orders()` | Halaman daftar order alternatif |

### API Internal (`/web/customer_service/`)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/web/customer_service` | `index()` | List pesanan (DataTable) |
| POST | `/web/customer_service` | `store()` | Buat pesanan baru |
| GET | `/web/customer_service/{refId}` | `show()` | Detail pesanan |
| PUT | `/web/customer_service/{refId}` | `update()` | Update pesanan |
| POST | `/web/customer_service/deletes` | `destroy()` | Hapus pesanan (bulk) |
| GET | `/web/customer_service/export/{refId}` | `export()` | Export PDF pesanan |
| GET | `/web/customer_service/export_rincian/{refId}` | `exportrincian()` | Export PDF rincian biaya |

---

## Alur Kerja

```
1. CS menerima pesanan telepon/langsung dari pelanggan
        ↓
2. CS membuka halaman /customer_service
        ↓
3. CS mencari pelanggan (search by nama/telepon)
   - Jika pelanggan sudah ada → pilih dari daftar
   - Jika baru → ketik nama baru → otomatis dibuat pelanggan baru
        ↓
4. CS mengisi form pesanan:
   - Tanggal event, waktu, venue
   - Jumlah tamu (total_guest) & undangan (total_invite)
   - Jenis event (pernikahan, sunatan, dll) — bisa multi pilih
   - Paket menu (package_type) — bisa multi pilih
   - Menu-menu yang dipesan (dari katalog menus_catering)
   - Rincian biaya (transport, dekorasi, dll)
   - Uang muka (DP)
        ↓
5. Submit → sistem:
   a. Cek apakah customer_id adalah UUID valid
      - Jika ya → gunakan pelanggan yang sudah ada
      - Jika tidak → buat pelanggan baru dengan nama tersebut
   b. Buat record Orders
   c. Buat record OrderItems untuk setiap menu dipilih
      - Harga per item = (selling_price / porsi_standard) × quantity
      - Jika kategori is_request=false → quantity = total_guest
   d. Buat record RincianBiaya
   e. Generate PDF otomatis
        ↓
6. PDF tersimpan di storage/report_order/order_{id}.pdf
7. PDF URL dikembalikan sebagai response
```

---

## Model Orders — Field Detail

| Field | Tipe | Keterangan |
|-------|------|-----------|
| `id` | ULID | Primary key |
| `customer_id` | FK | Referensi ke customers |
| `order_ticket` | string | Nomor tiket format `LL-YYMMDDHHMM-XXX` |
| `estimate_price` | decimal | Estimasi harga total |
| `delivery_date` | datetime | Waktu antar/pengiriman |
| `event_date` | datetime | Tanggal pelaksanaan event |
| `event_time` | datetime | Waktu mulai event |
| `total_guest` | int | Jumlah tamu yang makan |
| `total_invite` | int | Jumlah total undangan |
| `status` | enum | `pending`, `approved`, `purchased`, `cancelled` |
| `desc` | text | Catatan umum pesanan |
| `desc_extra` | text | Catatan tambahan/khusus |
| `event_type` | string | Jenis event (comma-separated) |
| `package_type` | string | Paket yang dipilih (comma-separated) |
| `venue` | string | Lokasi/tempat event |
| `dp` | decimal | Down payment / uang muka |

---

## Kalkulasi Harga Item

```php
// Rumus harga per item:
$harga_per_porsi = $selling_price / $porsi_standard;
$total_harga_item = $harga_per_porsi × $quantity_pesanan;

// Contoh:
// Nasi Putih: selling_price=500.000, porsi_standard=100
// Harga per porsi = 500.000 / 100 = 5.000
// Untuk 200 tamu: 5.000 × 200 = 1.000.000
```

---

## Filter Daftar Pesanan

Parameter query yang didukung saat listing:

| Parameter | Nilai | Keterangan |
|-----------|-------|-----------|
| `search` | string | Cari berdasarkan nama/telepon pelanggan |
| `date` | YYYY-MM | Filter bulan (default: bulan ini) |
| `orders` | `event` atau kosong | Urutkan/filter by tanggal event atau tanggal dibuat |
| `device` | `web`, `stealth` | Format output (HTML DataTable vs JSON) |

---

## Export PDF

### Laporan Pesanan (`export/{refId}`)

Data yang dimuat dalam PDF:
- Informasi pesanan (nomor tiket, tanggal, venue)
- Data pelanggan + alamat lengkap
- Daftar menu yang dipesan (dikelompokkan per kategori)
- QR Code dengan nomor order
- Tanggal dalam format Indonesia (Senin, 27 Januari 2025)

**Nama file**: `order_{id}.pdf`
**Lokasi**: `storage/report_order/`
**Template**: `resources/views/export_pdf/customer_services.blade.php`

### Laporan Rincian Biaya (`export_rincian/{refId}`)

Data yang dimuat:
- Informasi event
- Daftar rincian biaya (nama, qty, harga)
- Total biaya

**Nama file**: `rincian_{id}.pdf`
**Template**: `resources/views/export_pdf/rincian_biaya.blade.php`

---

## Relasi Model Orders

```php
// Orders → banyak OrderItems
$order->refItem

// Orders → banyak RincianBiaya
$order->rincianbiaya

// Orders → satu Customers
$order->customer

// Orders → satu CostEstimations
$order->costestimation

// Orders → satu Purchases
$order->purchases

// Orders → satu Users (CS yang membuat)
$order->petugas
```

---

## Contoh Request Store

```json
POST /web/customer_service

{
  "customer_id": "pelanggan baru",        // atau UUID pelanggan existing
  "phone": "081234567890",                // jika customer baru
  "address": "Jl. Merdeka No.1",         // jika customer baru
  "event_date": "2025-02-14 10:00:00",
  "event_time": "2025-02-14 10:00:00",
  "delivery_date": "2025-02-14 07:00:00",
  "total_guest": "200",
  "total_invite": "500",
  "venue": "Gedung Serbaguna",
  "event_type": ["pernikahan"],
  "package_type": ["paket-gold"],
  "estimate_price": "5000000",
  "dp": "1.000.000",
  "desc": "Mohon menu vegetarian untuk 20 tamu",
  "status": "pending",
  "item": {
    "menu-ulid-1": {
      "menus_catering_id": "menu-ulid-1",
      "quantity": "200",
      "price": "500000",
      "porsi_standard": "100",
      "is_request": "0",
      "notes": ""
    }
  },
  "rincian": {
    "new-1": {
      "name": "Biaya Transport",
      "qty": "1",
      "price": "500.000"
    }
  }
}
```

---

## Status Workflow

```
[pending]
    ↓ (Cost Controlling verifikasi)
[approved]
    ↓ (Purchasing buat PO)
[purchased]

[pending/approved]
    → [cancelled]  (pembatalan)
```
