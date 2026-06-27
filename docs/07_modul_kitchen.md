# 07 — Modul Kitchen (Dapur)

## Deskripsi

Modul **Kitchen** memberikan visibilitas kepada tim dapur tentang pesanan yang perlu dimasak. Tim dapur dapat melihat daftar menu yang dipesan beserta bahan baku yang dibutuhkan sesuai resep, dan mengekspor tugas memasak sebagai dokumen kerja.

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/KitchenController.php` |
| API Controller | `app/Http/Controllers/Api/KitchenApiController.php` |
| Repository | `app/Repository/KitchenRepository.php` |
| Resource | `app/Http/Resources/KitchenResource.php` |
| Models | `Orders`, `OrderItems`, `MenusCatering`, `MenusCateringIngredients`, `Ingredients` |

---

## Route

### Web (Blade Pages)

| Method | URL | Keterangan |
|--------|-----|-----------|
| GET | `/kitchen` | Halaman daftar pesanan untuk dapur |

### API Internal (`/web/kitchen/`)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/web/kitchen` | `index()` | List pesanan (DataTable) |
| GET | `/web/kitchen/{refId}` | `show()` | Detail pesanan + resep |
| GET | `/web/kitchen/export/{refId}` | `export()` | Export PDF tugas dapur |

---

## Alur Kerja

```
1. Pesanan sudah diverifikasi (status "approved")
        ↓
2. Tim dapur membuka /kitchen
        ↓
3. Melihat daftar pesanan bulan ini
   (bisa filter by tanggal event atau tanggal buat)
        ↓
4. Membuka detail pesanan:
   - Lihat daftar menu yang harus dimasak
   - Setiap menu menampilkan bahan baku (dari resep)
   - Informasi: nama bahan, jumlah, satuan
        ↓
5. Export PDF "Tugas Dapur" untuk panduan memasak
        ↓
6. Tim dapur memasak sesuai resep
```

---

## Data yang Ditampilkan

### Daftar Pesanan (index)

Kolom yang ditampilkan di DataTable:

| Kolom | Sumber | Keterangan |
|-------|--------|-----------|
| No. Tiket | `orders.order_ticket` | Nomor pesanan |
| Tanggal Event | `orders.event_date` | Kapan event berlangsung |
| Tanggal Antar | `orders.delivery_date` | Kapan makanan diantar |
| Catatan | `orders.desc` | Catatan khusus dari CS |
| Jumlah Menu | count(`order_items`) | Total menu yang dipesan |
| Dibuat | `orders.created_at` | Tanggal order dibuat |

### Detail Pesanan (show)

Data yang dikirim via `KitchenResource`:

```json
{
  "id": "order-ulid",
  "order_ticket": "LL-2601270900-XYZ",
  "delivery_date": "2026-01-27 07:00:00",
  "event_date": "2026-01-27 10:00:00",
  "ref_item": [
    {
      "id": "item-ulid",
      "menu": {
        "id": "menu-ulid",
        "name": "Nasi Putih",
        "porsi_standard": 100,
        "selling_price": 500000,
        "category": { "id": "cat-ulid", "name": "Makanan Pokok" },
        "menuingredients": [
          {
            "ingredient_id": "ing-ulid",
            "ingredient_label": null,
            "quantity": 0.5,
            "ingredient": {
              "name": "Beras",
              "unit": "kg",
              "satuan": "gram"
            }
          },
          {
            "ingredient_id": null,
            "ingredient_label": "Garam secukupnya",
            "quantity": null
          }
        ]
      }
    }
  ]
}
```

---

## Jenis Bahan Baku dalam Resep

Ada dua jenis item dalam `menus_catering_ingredients`:

| Tipe | `ingredient_id` | `ingredient_label` | `quantity` |
|------|-----------------|-------------------|------------|
| **Bahan terstruktur** | Ada (FK) | null | Ada (decimal) |
| **Label bebas** | null | Teks bebas | null |

Contoh:
- Terstruktur: `ingredient_id=beras-ulid`, `quantity=0.5` (kg per porsi)
- Label bebas: `ingredient_label="Garam secukupnya"`, quantity=null

---

## Export PDF — Tugas Dapur

### Data yang Dimuat

- Informasi event:
  - Nomor tiket order
  - Tanggal event (format Indonesia: "Senin, 27 Januari 2026")
  - Venue
  - Jumlah undangan (`total_invite`)
- Per menu:
  - Nama menu
  - Kuantitas yang dipesan
  - Daftar bahan baku + jumlah + satuan
  - Label bebas (petunjuk tambahan)
- Catatan khusus dari CS

**Nama file**: `cost_{id}.pdf` *(disimpan di folder kitchen)*
**Lokasi**: `storage/report_kitchen/`
**Template**: `resources/views/export_pdf/kitchen_task.blade.php`

---

## Filter Daftar

| Parameter | Nilai | Keterangan |
|-----------|-------|-----------|
| `search` | string | Cari nama/telepon pelanggan |
| `status` | string | Filter status order |
| `date` | YYYY-MM | Filter bulan (default: bulan ini) |
| `orders` | `event` | Sort/filter by tanggal event |
| `device` | `web`, `stealth` | Format output |

---

## Relasi Data

```
Orders
  └── order_items (menu yang dipesan)
        └── menus_catering (detail menu)
              ├── category_menus_catering (kategori menu)
              └── menus_catering_ingredients (resep)
                    └── ingredients (bahan baku)
                          ├── name
                          ├── unit (satuan beli: kg, liter)
                          └── satuan (satuan kecil: gram, ml)
```

---

## Catatan Teknis

- Modul Kitchen bersifat **read-only** — tim dapur hanya bisa melihat dan export
- Tidak ada operasi create/update/delete di modul ini
- Filter bulan menggunakan `event_date` atau `created_at` sesuai parameter `orders`
- Pesanan dengan status apapun bisa dilihat (tidak ada filter status default)
