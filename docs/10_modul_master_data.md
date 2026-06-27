# 10 — Modul Master Data

## Deskripsi

**Master Data** adalah kumpulan data referensi yang digunakan oleh modul-modul transaksi seperti Menu, Customer Service, dan Purchasing. Manajemen master data meliputi entitas pelanggan, pemasok (supplier), bahan baku, kategori, dan data referensi wilayah.

---

## Daftar Entitas Master Data

| Modul | Endpoint Prefix | Model | Keterangan |
|-------|----------------|-------|------------|
| **Customers** | `/web/customers` | `Customers` | Data pelanggan |
| **Suppliers** | `/web/suppliers` | `Suppliers` | Data pemasok bahan baku |
| **Ingredients** | `/web/ingredients` | `Ingredients` | Master bahan baku katering |
| **Category Menus** | `/web/category_menus` | `CategoryMenusCatering` | Kategori menu (Misal: Utama, Penutup) |
| **Packet Menus** | `/web/packet_menus` | `PacketCatering` | Paket hidangan bundel |
| **Ref Wilayah** | `/web/ref_wilayah` | `RefProvince` dll. | Data wilayah kelurahan/kecamatan/kota |
| **Cost Structure** | `/web/cost_stucture`| `CostStructures` | Template komponen biaya produksi |

---

## Arsitektur CRUD Master Data

Hampir semua master data memiliki arsitektur yang seragam, terdiri atas:
1. Controller Web untuk load Blade View (misal `CustomersController`)
2. Controller API untuk logic JSON (misal `CustomersApiController`)
3. Repository untuk proses query ke Database (misal `CustomersRepository`)

### Endpoint API Standar (JSON)

| Method | Endpoint | Fungsi |
|--------|----------|--------|
| `GET` | `/` | Menampilkan data dalam format DataTable. Menerima query parameter `search`, `device=web/stealth/mobile`. |
| `POST` | `/` | `store()` — Menambah data baru. |
| `GET` | `/search` | Fitur select2/autocomplete untuk dropdown (mereturn ID dan text/nama). |
| `GET` | `/{refId}` | `show()` — Mengambil data spesifik berdasarkan ULID. |
| `PUT` | `/{refId}` | `update()` — Mengubah data spesifik berdasarkan ULID. |
| `POST` | `/deletes`| `destroy()` — Menerima array ULID untuk dihapus massal (Bulk Soft Delete). |

---

## 1. Customers (Pelanggan)

Data profil pelanggan. Dilengkapi fungsi logging perubahan menggunakan `Spatie\Activitylog`.
- Berisi info nama, nomor HP, alamat, gender.
- Terhubung dengan `RefVilage` (Kelurahan) via `vilage_id`.
- Dihapus menggunakan Soft Delete (`deleted_at`).

## 2. Suppliers (Pemasok)

Pemasok barang dapur untuk modul Purchasing.
- Berisi nama toko/supplier, PIC (penanggung jawab), no telepon, dan alamat.
- Modul purchasing dapat menambah entri supplier baru secara *on-the-fly* jika user mengetik nama supplier baru yang belum terdaftar.

## 3. Ingredients (Bahan Baku)

Master bahan baku komponen dasar resep dapur.
- `unit`: Satuan beli utama (contoh: "kg").
- `satuan`: Satuan pemakaian terkecil di dapur (contoh: "gram").
- `default_price`: Harga patokan awal.
- Relasi: Memiliki tabel `ingredients_suppliers` untuk memetakan bahan baku A biasanya dipasok oleh Supplier B (default).

## 4. Category & Packet Menus

### Category
Kategori digunakan untuk mengelompokkan `MenusCatering`.
Memiliki parameter `is_quantity` yang menentukan apakah menu dalam kategori tersebut bisa dikali jumlah (qty) sesuai tamu atau dihitung statis.

### Packet Menus
Bundling dari beberapa menu. Relasinya disimpan di tabel pivot `packet_menus_catering`.

## 5. Ref Wilayah (Wilayah Administratif)

Data referensi berjenjang (Cascading) yang digunakan untuk isian alamat (terutama Customer Service & Employes):
- Provinsi (`ref_provinces`)
- Kota/Kabupaten (`ref_cities`)
- Kecamatan (`ref_districts`)
- Kelurahan/Desa (`ref_vilages`)

*Endpoint khusus:*
- `/web/ref_wilayah/province-city` — Ambil kota berdasarkan ID provinsi.
- `/web/ref_wilayah/district-vilage` — Ambil desa berdasarkan ID kecamatan.
*Catatan:* Primary key tabel ref_wilayah menggunakan tipe Integer, bukan ULID.

## 6. Cost Structure

Berfungsi sebagai template standar dalam modul *Cost Controlling*.
Mendefinisikan komponen pengeluaran (misal: "Gaji Crew", "Transport", "Bahan Baku") beserta persentase tetap yang digunakan setiap ada penghitungan laba kotor di order baru.
