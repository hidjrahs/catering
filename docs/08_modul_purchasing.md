# 08 — Modul Purchasing

## Deskripsi

Modul **Purchasing** (Pembelian) digunakan untuk mengelola pembelian bahan baku berdasarkan pesanan katering yang sudah disetujui (berstatus `approved`). Modul ini mengelompokkan bahan baku dari pesanan, mengaitkannya dengan supplier, dan mengekspor laporan Purchase Order (PO).

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/PurchasingController.php` |
| API Controller | `app/Http/Controllers/Api/PurchasingApiController.php` |
| Repository | `app/Repository/PurchasingRepository.php` |
| Resource | `app/Http/Resources/PurchasingResource.php` |
| Models | `Purchases`, `PurchasesItems`, `Suppliers`, `Ingredients`, `Orders` |

---

## Route

### Web (Blade Pages)

| Method | URL | Keterangan |
|--------|-----|-----------|
| GET | `/purchasing` | Halaman daftar pesanan yang perlu/sudah dibeli |

### API Internal (`/web/purchasing/`)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/web/purchasing` | `index()` | List order siap beli |
| GET | `/web/purchasing/batch` | `batch()` | Laporan batch berdasarkan rentang waktu |
| POST | `/web/purchasing` | `store()` | Simpan data pembelian (PO) |
| POST | `/web/purchasing/batch` | `batch_report()`| Export Laporan Batch PDF |
| GET | `/web/purchasing/export/{refId}` | `export()` | Export PDF PO per Order |
| POST | `/web/purchasing/{refId}` | `update()` | Update PO existing |
| GET | `/web/purchasing/{refId}` | `show()` | Detail pesanan + list PO |

---

## Alur Kerja

```
1. Purchasing membuka /purchasing
        ↓
2. Memilih pesanan dengan status "approved"
        ↓
3. Sistem memuat daftar bahan baku dari menu pesanan
        ↓
4. Tim Purchasing mengisi form PO:
   - Menentukan supplier untuk setiap bahan baku
   - (Jika nama supplier baru diketik → sistem otomatis menambah ke master Suppliers)
   - Mengisi jumlah beli & harga aktual
        ↓
5. Submit → Sistem:
   a. Membuat record Purchases & PurchasesItems
   b. Mengubah status order menjadi "purchased"
        ↓
6. Laporan PO bisa diekspor per Order (PDF)
        ↓
7. Laporan PO Batch:
   Tim bisa memilih rentang tanggal event, dan mengekspor akumulasi 
   kebutuhan bahan baku yang dikelompokkan per Supplier.
```

---

## Model Pembelian

```
purchases
├── id (ULID, PK)
├── order_id (FK → orders.id)
├── user_id (FK → users.id)
├── purchase_date (date)
└── timestamps

purchases_items
├── id (ULID, PK)
├── purchase_id (FK → purchases.id)
├── ingredient_id (FK → ingredients.id)
├── supplier_id (FK → suppliers.id)
├── quantity (decimal)
├── price (decimal)
└── timestamps
```

---

## Pembuatan Supplier Otomatis

Pada saat menyimpan data pembelian (`store`), sistem akan mengecek apakah ID supplier berupa ULID (panjang 26 karakter). Jika bukan (artinya user mengetikkan nama supplier baru yang belum ada di database), sistem akan otomatis membuatnya:

```php
// Snippet dari PurchasingRepository::store()
$supplier = $item['supplier_id'];
if(in_array($item['supplier_id'], $listSupplier)){
    if(strlen($supplier) !== 26) { // Bukan ULID
        $saveSupplier = Suppliers::create(['name' => $item['supplier_id']]);
        $supplier = $saveSupplier->id;
    }
}
```

---

## Laporan Batch (Batch Export)

Fungsi `exportBatch` di repository memungkinkan akumulasi pembelian dari beberapa order dalam rentang waktu tertentu. 

- Data diagregasi berdasarkan **Supplier**, lalu dikelompokkan berdasarkan **Bahan Baku** (`ingredient_id`).
- Jika pesanan membutuhkan Beras 10 kg dan pesanan lain Beras 15 kg dari supplier yang sama, laporan akan merangkum menjadi total 25 kg Beras.
- **File Output**: `batch_{rentang_tanggal}.pdf`
- **Template**: `export_pdf.purchasing_batch`

---

## Ekspor PO (Single Order Export)

Fungsi `export` membuat lembar Purchase Order untuk 1 pesanan.
- Dikelompokkan per **Supplier**.
- Termasuk QR Code nomor order.
- **File Output**: `purchase_{order_id}.pdf`
- **Template**: `export_pdf.purchasing`
