# 06 — Modul Cost Controlling

## Deskripsi

Modul **Cost Controlling** bertugas memverifikasi estimasi biaya pesanan sebelum diproses lebih lanjut ke dapur dan pembelian. Tim cost controlling menghitung margin keuntungan, menyesuaikan porsi menu, dan memberikan persetujuan akhir atas harga jual ke pelanggan.

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/CostControlingController.php` |
| API Controller | `app/Http/Controllers/Api/CostControlingApiController.php` |
| Repository | `app/Repository/CostControlingRepository.php` |
| Resource | `app/Http/Resources/CostControlingResource.php` |
| Models | `Orders`, `OrderItems`, `CostEstimations`, `CostEstimationDetail`, `RincianBiaya` |

---

## Route

### Web (Blade Pages)

| Method | URL | Keterangan |
|--------|-----|-----------|
| GET | `/cost_controling` | Halaman daftar pesanan untuk diverifikasi |

### API Internal (`/web/cost_controling/`)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/web/cost_controling` | `index()` | List pesanan (DataTable) |
| POST | `/web/cost_controling` | `verify()` | Verifikasi & approve pesanan |
| POST | `/web/cost_controling/update` | `update()` | Update estimasi biaya |
| GET | `/web/cost_controling/{refId}` | `show()` | Detail pesanan + estimasi |
| GET | `/web/cost_controling/export/{refId}` | `export()` | Export PDF cost control |
| GET | `/web/cost_controling/exportsr/{refId}` | `exportsr()` | Export PDF surat resep dapur |

---

## Alur Kerja

```
1. Cost Controller membuka /cost_controling
        ↓
2. Melihat daftar pesanan dengan status "pending"
        ↓
3. Membuka detail pesanan:
   - Lihat daftar menu yang dipesan
   - Lihat bahan baku yang dibutuhkan (dari resep menu)
   - Lihat rincian biaya dari CS
        ↓
4. Melakukan kalkulasi:
   a. Estimasi biaya produksi (estimated_cost)
   b. Penyesuaian porsi menu jika perlu
   c. Pilih struktur biaya (template cost structure)
   d. Isi detail biaya per komponen (prosentase/fixed)
   e. Tentukan harga jual (estimated_selling_price)
   f. Kalkulasi margin (estimated_margin)
        ↓
5. Submit verifikasi → sistem:
   a. Update porsi menu di order_items jika ada perubahan
   b. Update harga order (estimate_price) di tabel orders
   c. Update status orders → "approved"
   d. Simpan record CostEstimations
   e. Simpan detail CostEstimationDetail
   f. Update RincianBiaya
        ↓
6. Generate PDF laporan cost control
7. Generate PDF surat resep (SR) untuk dapur
```

---

## Model CostEstimations — Field Detail

| Field | Tipe | Keterangan |
|-------|------|-----------|
| `id` | ULID | Primary key |
| `order_id` | FK | Referensi ke orders |
| `estimated_cost` | decimal | Total estimasi biaya produksi |
| `estimated_selling_price` | decimal | Harga jual yang disetujui |
| `estimated_margin` | decimal | Margin keuntungan |
| `desc` | text | Catatan verifikasi |
| `verified_by` | FK → users | User yang memverifikasi |
| `cost_structure_id` | FK | Template struktur biaya yang dipakai |

---

## Cost Structure (Template Biaya)

Sistem menyediakan template struktur biaya yang bisa digunakan berulang:

```
cost_structures
  └── cost_structure_detail (komponen biaya)
        ├── name: "Biaya Bahan Baku"
        ├── fixed: false
        ├── kategori: "bahan_baku"
        ├── prosentase: 60  (% dari harga jual)
        └── prosentase_price: null

cost_estimation_detail (aktual per order)
  ├── name: "Biaya Bahan Baku"
  ├── fixed: false / true
  ├── kategori: "bahan_baku"
  ├── prosentase: 60
  ├── prosentase_price: 3.000.000  (hasil perhitungan)
  ├── fixed_price: null            (atau nilai fixed)
  └── fixed_qty: null              (qty jika ada)
```

### Tipe Komponen Biaya

| Tipe | Keterangan | Kalkulasi |
|------|-----------|-----------|
| **Prosentase** | Persentase dari harga jual | `harga_jual × prosentase / 100` |
| **Fixed** | Biaya tetap | Nilai langsung dari `fixed_price` |

---

## Penyesuaian Porsi Menu

Cost Controlling bisa menyesuaikan porsi setiap menu dalam order:

```php
// Input dari form:
// porsi[{menus_catering_id}] = nilai_porsi_baru
// notes[{menus_catering_id}] = catatan

// Sistem otomatis menghitung ulang harga:
$harga_baru = ($selling_price / $porsi_standard) × $porsi_baru;

// Update order_items:
OrderItems::where([
    'order_id' => $orderId,
    'menus_catering_id' => $menuId
])->update([
    'quantity' => $porsi_baru,
    'price' => $harga_baru,
    'notes' => $catatan
]);
```

---

## Export PDF

### Laporan Cost Control (`export/{refId}`)

Data yang dimuat:
- Info pesanan lengkap
- Daftar menu yang dipesan
- Estimasi biaya (cost, selling price, margin)
- Detail komponen biaya (struktur biaya)
- QR Code nomor order

**Nama file**: `cost_{id}.pdf`
**Lokasi**: `storage/report_cost_control/`
**Template**: `resources/views/export_pdf/cost_control.blade.php`

### Surat Resep Dapur (`exportsr/{refId}`)

Data yang dimuat:
- Info event (tanggal, venue, jumlah undangan)
- Daftar menu + bahan baku yang dibutuhkan (dari resep)
- Satuan bahan baku

**Nama file**: `sr_{id}.pdf`
**Lokasi**: `storage/report_cost_control/`
**Template**: `resources/views/export_pdf/cost_control_sr.blade.php`

---

## Filter Daftar Pesanan

| Parameter | Nilai | Keterangan |
|-----------|-------|-----------|
| `search` | string | Cari nama/telepon pelanggan |
| `status` | string | Filter status order |
| `date` | YYYY-MM | Filter bulan |
| `orders` | `event` | Sort by event_date atau created_at |
| `device` | `web`, `stealth` | Format output DataTable |

---

## Contoh Request Verify

```json
POST /web/cost_controling

{
  "id": "order-ulid",
  "estimated_cost": "3.000.000",
  "estimated_selling_price": "5.000.000",
  "estimated_margin": "2.000.000",
  "cost_structure_id": "struktur-ulid",
  "porsi": {
    "menu-ulid-1": "200",
    "menu-ulid-2": "150"
  },
  "notes": {
    "menu-ulid-1": "Porsi disesuaikan"
  },
  "structure": [
    {
      "name": "Biaya Bahan Baku",
      "kategori": "bahan_baku",
      "prosentase": "60",
      "prosentase_price": "3000000",
      "fixed_price": null
    },
    {
      "name": "Biaya Operasional",
      "kategori": "operasional",
      "fixed_price": "500.000",
      "prosentase": "0"
    }
  ],
  "rincian": {
    "rincian-ulid-1": {
      "name": "Biaya Transport",
      "qty": "1",
      "price": "500.000"
    }
  }
}
```

---

## Relasi Model

```php
// CostEstimations
$estimation->order          // → Orders
$estimation->detail         // → CostEstimationDetail (many)
$estimation->cost_structure // → CostStructures

// Orders (via cost controlling)
$order->costestimation      // → CostEstimations (one)
$order->refItem             // → OrderItems (many) + menu + bahan baku
$order->rincianbiaya        // → RincianBiaya (many)
```
