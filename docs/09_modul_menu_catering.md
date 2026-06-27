# 09 — Modul Menu Catering

## Deskripsi

Modul **Menu Catering** mengelola katalog produk yang dijual oleh katering, termasuk harga jual, porsi standar, kategori menu, dan resep (komposisi bahan baku). Modul ini juga mendukung **Impor Masal via Excel** menggunakan Background Jobs.

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/MenuCateringController.php` |
| API Controller | `app/Http/Controllers/Api/MenuCateringApiController.php` |
| Repository | `app/Repository/MenuCateringRepository.php`, `ImportRepository.php` |
| Resource | `MenuCateringDetailResource`, `MenuCateringSelectResource` |
| Models | `MenusCatering`, `MenusCateringIngredients`, `PacketMenusCatering`, `CategoryMenusCatering`, `ImportBerkas`, `TempRecipeMenu`, `TempIngredientsMenu` |
| Jobs/Import | `GenerateImportExcel`, `GenerateRecipe`, `RecipeMenuImport` |

---

## Route

### Web (Blade Pages)

| Method | URL | Keterangan |
|--------|-----|-----------|
| GET | `/menus_catering` | Halaman daftar menu katering |
| GET | `/menus_catering/import` | Halaman proses import massal |

### API Internal (`/web/menus_catering/`)

| Method | URL | Action | Keterangan |
|--------|-----|--------|-----------|
| GET | `/web/menus_catering` | `index()` | List Menu (DataTable) |
| POST | `/web/menus_catering` | `store()` | Buat menu & resep baru |
| GET | `/web/menus_catering/select` | `select()` | Dropdown menu select2 |
| GET | `/web/menus_catering/generate`| `generate()` | Download template Excel Import |
| GET | `/web/menus_catering/batch` | `list_batch()`| List status import batch |
| POST | `/web/menus_catering/batch` | `store_batch()`| Upload Excel untuk import |

---

## Model Menu & Resep

```
menus_catering
├── id (ULID, PK)
├── category_menus_catering_id (FK)
├── name (string)
├── selling_price (decimal)
├── porsi_standard (decimal)
├── is_active (boolean)
└── timestamps

menus_catering_ingredients (Bahan Baku / Resep)
├── id (ULID, PK)
├── menus_catering_id (FK)
├── ingredient_id (FK, nullable)
├── ingredient_label (string, nullable) 
└── quantity (decimal, nullable)
```

**Konsep Resep:**
1. **Terstruktur**: Menggunakan `ingredient_id` yang me-refer ke tabel `ingredients`, dan mencantumkan `quantity` aktual. Digunakan untuk perhitungan COGS & stok.
2. **Bebas (Label)**: Menggunakan `ingredient_label` untuk petunjuk/bahan tambahan yang tidak terukur mutlak (contoh: "Garam secukupnya").

---

## Fitur Impor Excel Masal

Karena input resep sangat kompleks jika dilakukan satu per satu, sistem menyediakan fitur import berbasis file Excel (`maatwebsite/excel`).

### Alur Proses Impor:

1. **Unduh Template (`/generate`)**:
   Sistem membuat background job `GenerateImportExcel` untuk menghasilkan file Excel berisi 2 sheet:
   - Sheet 1: Format Master Menu & Resep
   - Sheet 2: Referensi ID Bahan Baku yang ada di database

2. **Upload & Antrian (`store_batch`)**:
   - User mengunggah file Excel yang sudah diisi.
   - Sistem mencatat di tabel `import_berkas` dengan status `queue`.
   - Menggunakan `RecipeMenuImport` dari maatwebsite, file diuraikan dan datanya dimasukkan ke tabel staging (sementara):
     - `temp_recipe_menu` (Data Menu)
     - `temp_ingredients_menu` (Data Bahan Baku Resep)

3. **Eksekusi Background Job (`GenerateRecipe`)**:
   - Job membaca data staging.
   - Melakukan sinkronisasi kategori, menu, dan komposisi resep.
   - Menandai tabel `import_berkas` menjadi `success` jika berhasil, atau mencatat error di kolom `error` jika gagal.
   - Menghapus data temporary dari tabel staging.

> **Perhatian:**
> Fitur import ini **wajib** menggunakan worker queue di latar belakang.
> Perintah: `php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5`

---

## Harga dan Porsi Standar

Harga jual katering diatur berdasarkan porsi standar (base portion). 

Contoh: 
- Menu "Gulai Kambing" 
- `selling_price` = Rp 1.000.000 
- `porsi_standard` = 50 
- Harga per porsi = 1.000.000 / 50 = Rp 20.000.

Saat Customer Service memesan 150 porsi, harga pesanan otomatis menjadi `(1.000.000 / 50) * 150 = 3.000.000`. Dan bahan baku resep juga akan dikali 3x lipat oleh sistem Kitchen.
