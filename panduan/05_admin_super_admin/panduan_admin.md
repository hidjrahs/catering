# ⚙️ Panduan Pengguna — Admin & Super Admin

**Role:** `admin` / `super_admin`  
**Akses URL Utama:** `http://localhost:8008/home`

---

## Deskripsi Peran

### Super Admin
Memiliki akses **penuh** ke seluruh sistem tanpa batasan apapun. Bertanggung jawab atas:
- Konfigurasi awal sistem
- Manajemen semua pengguna dan role
- Backup & restore data
- Konfigurasi menu sidebar
- Semua fungsi admin di bawah ini

### Admin
Memiliki akses ke pengelolaan operasional sistem harian:
- Manajemen pengguna (tambah, edit, nonaktifkan)
- Pengelolaan seluruh master data
- Konfigurasi role & permission
- Profil usaha (informasi bisnis)

> 🔒 Perbedaan: **Super Admin** bisa mengakses semua fitur tanpa batasan VPN, sementara beberapa menu Admin mungkin dibatasi.

---

## Navigasi Menu Admin

Dari sidebar, Admin & Super Admin bisa mengakses **semua menu**:

```
📊 Dashboard
📋 Transaksi
   ├── Customer Service
   ├── Cost Controlling
   ├── Purchasing
   ├── Kitchen
   └── Manajemen Stok
📦 Master
   └── Data Master
         ├── Data Customer
         ├── Data Supplier
         ├── Data Barang (Bahan Baku)
         ├── Data Paket Menu
         ├── Data Kategori Menu
         ├── Data Menu Catering
         ├── Data Karyawan
         └── Referensi Wilayah
⚙️ Pengaturan
   ├── Sidebar (Menu Navigasi)
   ├── Daftar User
   ├── Role Permission
   ├── Profil Usaha
   └── Backup Data
```

---

## Bagian A: Manajemen Pengguna

### A1. Melihat Daftar User

1. Buka **Pengaturan → Daftar User**
2. Tabel menampilkan semua pengguna sistem
3. Bisa dicari berdasarkan nama atau email

### A2. Menambah User Baru

1. Klik tombol **"+ Tambah User"**
2. Isi data:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Nama** | Nama lengkap pengguna | ✅ |
| **Email** | Alamat email (digunakan untuk login) | ✅ |
| **Password** | Kata sandi awal | ✅ |
| **Role** | Peran pengguna di sistem | ✅ |

3. Klik **"Simpan"**

> 💡 Informasikan email dan password awal kepada pengguna baru. Minta mereka segera mengubah password setelah login pertama.

### A3. Mengubah Data User

1. Cari user di daftar
2. Klik ikon ✏️ **Edit**
3. Ubah data yang diperlukan
4. Klik **"Simpan"**

> ⚠️ Jangan mengubah password pengguna kecuali diminta secara eksplisit.

### A4. Menonaktifkan User

Jika ada karyawan yang keluar atau tidak lagi menggunakan sistem:

1. Cari user di daftar
2. Klik ikon 🗑️ atau tombol **"Nonaktifkan"**
3. Konfirmasi tindakan

---

## Bagian B: Manajemen Role & Permission

### B1. Melihat Daftar Role

1. Buka **Pengaturan → Role Permission**
2. Daftar role yang tersedia:

| Role | Deskripsi |
|------|-----------|
| `super_admin` | Akses penuh tanpa batasan |
| `admin` | Kelola master data & pengaturan |
| `customer_service` | Input & kelola pesanan (perlu VPN) |
| `cost_control` | Verifikasi biaya pesanan (perlu VPN) |
| `purchasing` | Pembelian bahan baku (perlu VPN) |

### B2. Mengatur Role Pengguna

1. Buka **Pengaturan → Daftar User**
2. Edit pengguna yang ingin diubah rolenya
3. Pilih role dari dropdown
4. Simpan

> ⚠️ **Hati-hati:** Mengubah role pengguna akan langsung memengaruhi akses mereka ke sistem.

---

## Bagian C: Manajemen Master Data

### C1. Data Customer (Pelanggan)

**URL:** `/customers`

| Aksi | Cara |
|------|------|
| **Lihat daftar** | Buka halaman, tabel otomatis tampil |
| **Tambah baru** | Klik "+ Tambah", isi form, simpan |
| **Edit** | Klik ✏️ pada baris data |
| **Hapus** | Centang baris → klik "Hapus Terpilih" |

Field yang bisa diisi:
- Nama pelanggan
- Nomor HP
- Jenis kelamin
- Alamat lengkap (dengan referensi wilayah: Provinsi/Kota/Kecamatan/Kelurahan)

### C2. Data Supplier (Pemasok)

**URL:** `/suppliers`

Field yang bisa diisi:
- Nama toko/usaha supplier
- Nama PIC (penanggung jawab)
- Nomor HP/telepon
- Alamat

> 💡 Supplier juga bisa ditambahkan otomatis oleh tim Purchasing saat membuat PO.

### C3. Data Barang (Bahan Baku / Ingredients)

**URL:** `/ingredients`

Field yang bisa diisi:

| Field | Keterangan | Contoh |
|-------|------------|--------|
| **Nama** | Nama bahan baku | Beras, Minyak Goreng |
| **Unit** | Satuan beli utama | kg, liter, ikat |
| **Satuan** | Satuan pemakaian dapur | gram, ml, lembar |
| **Harga Default** | Harga patokan awal | 15.000 (per kg) |
| **Supplier Default** | Supplier utama bahan ini | Toko Sumber Makmur |

> 💡 Selisih unit dan satuan penting untuk perhitungan resep. Contoh: Beli dalam "kg" tapi resep dalam "gram".

### C4. Data Kategori Menu

**URL:** `/category_menus`

Field yang bisa diisi:
- **Nama Kategori** (contoh: Makanan Pokok, Lauk, Sayur, Minuman, Dessert)
- **Is Quantity** — apakah menu dalam kategori ini dihitung berdasarkan jumlah tamu?

| Is Quantity | Artinya |
|-------------|---------|
| ✅ Ya | Porsi = jumlah tamu (bisa disesuaikan) |
| ❌ Tidak | Porsi statis, tidak terpengaruh jumlah tamu |

### C5. Data Paket Menu

**URL:** `/packet_menus`

Paket adalah bundling dari beberapa menu. Contoh:
- **Paket Gold** = Nasi Putih + Ayam Bakar + Sop Buah + Minuman
- **Paket Silver** = Nasi Putih + Ayam Goreng + Es Teh

Cara tambah paket:
1. Isi nama paket
2. Pilih menu-menu yang termasuk dalam paket
3. Simpan

### C6. Data Menu Catering

**URL:** `/menus_catering`

Ini adalah katalog produk utama sistem. Setiap menu memiliki:

| Field | Keterangan |
|-------|------------|
| **Nama Menu** | Nama hidangan |
| **Kategori** | Kategori menu (dari master kategori) |
| **Harga Standar** | Harga untuk porsi standar |
| **Porsi Standar** | Berapa porsi dalam harga standar tersebut |
| **Resep** | Daftar bahan baku + jumlah yang dibutuhkan |
| **Status Aktif** | Apakah menu ini bisa dipesan |

#### Menambah Menu Secara Manual

1. Klik **"+ Tambah Menu"**
2. Isi nama, kategori, harga, dan porsi standar
3. Tambahkan bahan baku satu per satu:
   - Pilih bahan baku dari dropdown
   - Isi jumlah yang dibutuhkan per porsi standar
   - Atau ketik label bebas (untuk bahan yang tidak terukur)
4. Klik **Simpan**

#### Import Menu via Excel (Massal)

Untuk input banyak menu sekaligus:

1. Buka **"/menus_catering/import"** atau klik menu Import
2. **Download Template Excel:**
   - Klik **"Download Template"**
   - Tunggu file Excel tergenerate (diproses background)
   - Unduh file template
3. **Isi Template Excel:**
   - Sheet 1: Data menu (nama, kategori, harga, porsi)
   - Sheet 2: Referensi ID bahan baku yang ada di sistem
4. **Upload File:**
   - Klik **"Upload File Excel"**
   - Pilih file yang sudah diisi
   - Sistem akan memproses di background

> ⚠️ **PENTING:** Import Excel membutuhkan **Queue Worker** yang aktif.  
> Pastikan worker berjalan dengan perintah:  
> `php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5`

5. **Pantau Status Import:**
   - Lihat daftar batch import untuk melihat status
   - Status: `queue` (antri) → `processing` (diproses) → `success` / `error`

### C7. Data Karyawan

**URL:** `/employes`

Modul HRIS sederhana. Data yang bisa dikelola:

#### Data Utama Karyawan
| Field | Keterangan |
|-------|------------|
| **Nama** | Nama lengkap karyawan |
| **NIK** | Nomor Induk Kependudukan |
| **No. HP** | Nomor telepon |
| **Alamat** | Alamat tempat tinggal |
| **Status** | Tetap / Kontrak / Magang |
| **Divisi/Jabatan** | Posisi karyawan |

#### Data Tambahan (Opsional)
- **Riwayat Pendidikan** — tingkat, nama sekolah, jurusan, tahun lulus
- **Data Keluarga** — nama, hubungan, tanggal lahir
- **Kontak Darurat** — nama, hubungan, nomor HP
- **Data Kontrak** — tanggal berakhir kontrak, hasil wawancara

#### Membuat Akun Login untuk Karyawan

Di form tambah/edit karyawan, ada bagian **"Akun Sistem"**:

1. Isi **Username** (nama pengguna untuk login)
2. Isi **Email**
3. Isi **Password** awal
4. Sistem akan otomatis membuat akun di tabel `users` saat disimpan

> 💡 Dengan cara ini, Anda bisa membuat akun karyawan dan data karyawan sekaligus dalam satu form.

### C8. Referensi Wilayah

**URL:** `/ref_wilayah`

Data wilayah administratif Indonesia yang digunakan untuk isian alamat:
- Provinsi
- Kota/Kabupaten
- Kecamatan
- Kelurahan/Desa

> ℹ️ Data ini biasanya sudah di-seed dari data resmi. Hanya perlu diubah jika ada pemekaran wilayah baru.

---

## Bagian D: Pengaturan Sistem

### D1. Konfigurasi Menu Sidebar

**URL:** `/menus_sidebar`

Kelola tampilan menu di sidebar untuk setiap role:

1. Buka **Pengaturan → Sidebar**
2. Tambah, ubah, atau hapus item menu
3. Atur urutan tampilan

### D2. Profil Usaha

**URL:** `/profile_bussines`

Informasi bisnis yang muncul di laporan PDF:
- Nama usaha/perusahaan
- Alamat
- Nomor telepon
- Logo

### D3. Backup & Restore Data

**URL:** `/backup_restores`

> ⚠️ **Fitur khusus Super Admin**

1. **Backup:** Klik tombol "Backup Sekarang" — sistem akan membuat arsip database
2. **Restore:** Pilih file backup → konfirmasi restore

> 🚨 **HATI-HATI!** Restore akan menimpa semua data saat ini dengan data backup. Pastikan Anda yakin sebelum melakukan restore.

---

## Bagian E: Cost Structure (Template Struktur Biaya)

**URL:** `/cost_structure` *(di bawah Master Data)*

Template komponen biaya yang digunakan oleh Cost Controlling:

### Menambah Template Baru

1. Buka Cost Structure
2. Klik **"+ Tambah Template"**
3. Isi nama template (contoh: "Paket Pernikahan Standar")
4. Tambahkan komponen biaya:

| Komponen | Tipe | Nilai |
|----------|------|-------|
| Biaya Bahan Baku | Prosentase | 60% |
| Gaji Crew | Prosentase | 15% |
| Biaya Operasional | Fixed | Rp500.000 |
| Transport | Fixed | Rp300.000 |

5. Klik **Simpan**

---

## Tips & Best Practices untuk Admin

- 🔐 **Jangan berbagi akun admin** — setiap admin harus punya akun sendiri
- 📅 **Backup rutin** — lakukan backup minimal seminggu sekali
- 👀 **Pantau activity log** — semua perubahan data tercatat di sistem
- 🔄 **Update password berkala** — minta pengguna ganti password setiap 3 bulan
- 📊 **Verifikasi master data** — pastikan data bahan baku, supplier, dan menu selalu up-to-date

---

## Pertanyaan Umum (FAQ)

**Q: User tidak bisa login padahal credential benar?**  
A: Kemungkinan VPN tidak aktif (untuk role yang memerlukan VPN). Cek juga apakah akun masih aktif.

**Q: Import Excel gagal dengan status "error"?**  
A: Pastikan queue worker aktif. Cek format Excel sesuai template. Lihat pesan error di kolom "error" pada daftar batch import.

**Q: Bagaimana menambah menu baru ke sidebar?**  
A: Buka Pengaturan → Sidebar → tambah item menu baru dengan URL yang sesuai.

**Q: Apakah data yang dihapus bisa dipulihkan?**  
A: Data yang dihapus menggunakan soft delete (tidak benar-benar hilang dari database). Hubungi developer untuk memulihkan data yang terhapus.

---

*Terakhir diperbarui: Juni 2026*
