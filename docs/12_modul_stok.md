# 12 — Modul Manajemen Stok (Inventory)

## Deskripsi

Modul **Management Stok** diproyeksikan untuk mengontrol siklus masuk dan keluarnya bahan baku di dalam gudang (Inventory Management).

Saat ini modul stok **masih berupa scaffolding/kerangka awal** di dalam project, namun struktur database-nya telah dipersiapkan untuk pengembangan fitur inventory secara penuh (seperti penyesuaian/adjusment stok, in-out log, dan perhitungan nilai persediaan).

---

## Komponen Saat Ini

| Komponen | File | Keterangan |
|----------|------|-----------|
| Controller Web | `ManagementStokController.php` | Menampilkan View Halaman Stok |
| Controller API | `ManagementStokApiController.php` | Endpoint data (Placeholder) |
| Repository | `ManagementStokRepository.php` | Tempat implementasi query |
| Database | `stocks`, `stocks_movements` | Skema database yang disiapkan |

---

## Skema Database Terkait Stok

Struktur database yang dipersiapkan dalam migrasi (`2025_09_07_184259_create_stocks.php` & `_movements`):

### Tabel `stocks`
Menyimpan rangkuman kuantitas/saldo akhir per bahan baku.
```
stocks
├── id (ULID, PK)
├── ingredient_id (FK → ingredients.id)
├── quantity (decimal) — Saldo berjalan saat ini
└── timestamps
```

### Tabel `stocks_movements` (Kartu Stok)
Ledger pergerakan stok untuk keperluan tracking (Audit Trail In/Out).
```
stocks_movements
├── id (ULID, PK)
├── ingredient_id (FK → ingredients.id)
├── type (enum: 'in', 'out', 'adjustment')
├── quantity (decimal) — Jumlah masuk/keluar
├── reference_id (string, nullable) — ID PO, atau ID event jika keluar
├── notes (string, nullable)
├── user_id (FK → users.id)
└── timestamps
```

---

## Rencana Alur Kerja Integrasi (Future Blueprint)

Mengingat arsitektur katering yang terintegrasi, fitur ini nantinya akan dijembatani dengan proses-proses lain:

1. **Purchasing (In)**:
   Ketika status Purchase Order berubah menjadi "Barang Diterima", sistem akan menjalankan `trigger` atau `listener` untuk membuat record `type=in` di tabel `stocks_movements` dan menambah nilai `quantity` pada tabel `stocks`.

2. **Kitchen (Out)**:
   Saat pesanan event berjalan, status dapur selesai masak dapat memotong saldo stok. Item yang dipotong dihitung berdasarkan Resep (`menus_catering_ingredients`) dikali Porsi. Membutuhkan konversi matematis presisi tinggi antara `unit` (Pembelian) dan `satuan` (Pemakaian).

3. **Stock Opname (Adjustment)**:
   Admin gudang mencatat fisik bahan (Selisih plus/minus).

*(Catatan: Detail implementasi di atas bergantung pada fase pengembangan proyek katering berikutnya).*
