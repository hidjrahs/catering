# 🛒 Panduan Pengguna — Purchasing (Pembelian)

**Role:** `purchasing`  
**Akses URL Utama:** `http://localhost:8008/purchasing`

> ⚠️ **Perhatian:** Role ini memerlukan koneksi **VPN** untuk dapat mengakses sistem.

---

## Deskripsi Peran

Sebagai **Purchasing**, Anda bertanggung jawab atas pengadaan bahan baku untuk pesanan yang sudah disetujui (status: `approved`). Tugas utama Anda:

- Memantau pesanan yang siap untuk dibelikan bahan bakunya
- Membuat Purchase Order (PO) untuk setiap pesanan
- Menentukan supplier untuk setiap bahan baku
- Mencatat harga aktual pembelian
- Mencetak laporan PO (per pesanan maupun batch)
- Membuat laporan pembelian gabungan (batch) untuk beberapa event sekaligus

---

## Navigasi Menu Purchasing

Dari sidebar, Anda bisa mengakses:

```
🛒 Purchasing              → Kelola pembelian bahan baku
📦 Data Master
   └── Data Supplier       → Lihat/kelola data supplier
   └── Data Barang         → Lihat data bahan baku
```

---

## Alur Kerja Harian

```
Buka halaman Purchasing
       ↓
Lihat daftar pesanan "Approved"
       ↓
Pilih pesanan yang akan dibelikan
       ↓
Lihat daftar bahan baku yang dibutuhkan
       ↓
Tentukan supplier untuk setiap bahan baku
       ↓
Isi jumlah & harga aktual pembelian
       ↓
Simpan PO → status berubah ke "Purchased"
       ↓
Cetak laporan PO
```

---

## 1. Membuka Halaman Purchasing

1. Login ke sistem
2. Klik menu **"Purchasing"** di sidebar
3. Halaman daftar pesanan yang siap dibeli akan tampil

### Tampilan Daftar Pesanan

| Kolom | Keterangan |
|-------|------------|
| No. Tiket | Nomor tiket pesanan |
| Pelanggan | Nama pelanggan |
| Tanggal Event | Kapan event berlangsung |
| Status | Status pesanan (fokus pada "Approved") |
| Aksi | Tombol detail, buat PO, export |

### Filter Pesanan

| Filter | Cara Menggunakan |
|--------|-----------------|
| **Cari** | Ketik nama/no telepon pelanggan |
| **Status** | Filter berdasarkan status |
| **Bulan** | Pilih bulan yang ingin ditampilkan |

---

## 2. Membuat Purchase Order (PO) Baru

### Langkah 1: Pilih Pesanan

1. Cari pesanan berstatus **"Approved"**
2. Klik ikon 🛒 atau buka detail pesanan tersebut

### Langkah 2: Tinjau Daftar Bahan Baku

Sistem otomatis menampilkan semua bahan baku yang dibutuhkan berdasarkan:
- Menu yang dipesan
- Resep setiap menu
- Jumlah porsi yang sudah diverifikasi CC

Informasi yang ditampilkan:

| Info | Keterangan |
|------|------------|
| Nama Bahan Baku | Nama bahan yang perlu dibeli |
| Jumlah Kebutuhan | Total yang dibutuhkan (dari resep × porsi) |
| Satuan | Satuan beli (kg, liter, dll) |
| Menu Terkait | Menu mana yang membutuhkan bahan ini |

### Langkah 3: Tentukan Supplier

Untuk setiap bahan baku, tentukan suppliernya:

- **Supplier terdaftar:** Ketik nama supplier → pilih dari saran yang muncul
- **Supplier baru:** Ketik nama supplier yang belum terdaftar → sistem otomatis mendaftarkan supplier baru

> 💡 Setiap bahan baku bisa memiliki supplier yang berbeda.

### Langkah 4: Isi Jumlah & Harga Aktual

| Field | Keterangan |
|-------|------------|
| **Jumlah Beli** | Berapa unit yang akan dibeli (bisa berbeda dari kebutuhan jika ada pembelian minimal) |
| **Harga Aktual** | Harga per unit dari supplier hari ini |

> 💡 Harga aktual bisa berbeda dari harga estimasi. Catat harga sebenarnya saat membeli.

### Langkah 5: Simpan PO

1. Periksa kembali semua data supplier, jumlah, dan harga
2. Klik tombol **"Simpan PO"**
3. Sistem akan:
   - Membuat record PO di database
   - Mengubah status pesanan dari `approved` → **`purchased`**
4. Cetak PDF laporan PO

---

## 3. Mengubah PO yang Sudah Ada

Jika ada perubahan setelah PO disimpan:

1. Buka detail pesanan
2. Klik **"Edit PO"** atau ikon ✏️
3. Ubah data yang diperlukan (supplier, jumlah, harga)
4. Klik **"Simpan Perubahan"**

---

## 4. Export PDF Purchase Order (Per Pesanan)

1. Buka detail pesanan yang sudah ada PO-nya
2. Klik tombol **"Export PO"** (ikon 📄)
3. PDF akan berisi:
   - Info pesanan (nomor tiket, tanggal event)
   - Daftar bahan baku **dikelompokkan per supplier**
   - Jumlah dan harga per item
   - QR Code nomor order
   - Total pembelian per supplier

---

## 5. Laporan Batch (Pembelian Gabungan)

Fitur **Batch** memungkinkan Anda melihat dan mengekspor akumulasi kebutuhan bahan baku dari **beberapa pesanan sekaligus** dalam rentang waktu tertentu.

### Kapan Menggunakan Fitur Batch?

- Saat ada beberapa event dalam satu minggu
- Untuk efisiensi pembelian (beli sekaligus lebih murah)
- Untuk negosiasi harga dengan supplier berdasarkan volume

### Cara Menggunakan Laporan Batch

1. Di halaman Purchasing, klik tab/tombol **"Laporan Batch"**
2. Pilih **rentang tanggal event** (dari tanggal - sampai tanggal)
3. Klik **"Lihat Batch"**
4. Sistem akan menggabungkan semua kebutuhan bahan baku pada rentang tersebut

### Format Laporan Batch

Laporan dikelompokkan per **Supplier**, lalu per **Bahan Baku**:

```
📦 Supplier: Toko Sumber Makmur
   ├── Beras           → Total: 250 kg  (dari 3 event)
   ├── Gula Pasir      → Total: 50 kg   (dari 2 event)
   └── Minyak Goreng   → Total: 30 liter (dari 3 event)

📦 Supplier: UD Sayuran Segar
   ├── Wortel          → Total: 20 kg
   └── Kacang Panjang  → Total: 15 kg
```

### Export PDF Batch

1. Setelah melihat laporan batch, klik **"Export PDF Batch"**
2. PDF diberi nama: `batch_{rentang_tanggal}.pdf`
3. Gunakan untuk negosiasi dan pembelian ke supplier

---

## 6. Mengelola Data Supplier

Supplier bisa ditambahkan secara otomatis saat membuat PO, atau dikelola secara terpisah di Master Data.

### Cara Tambah/Edit Supplier via Master Data

1. Buka **Data Master → Data Supplier**
2. Klik **"+ Tambah Supplier"**
3. Isi data:
   | Field | Keterangan |
   |-------|------------|
   | **Nama Toko/Supplier** | Nama usaha supplier |
   | **PIC (Penanggung Jawab)** | Nama kontak di supplier |
   | **No. Telepon** | Nomor HP/telepon supplier |
   | **Alamat** | Alamat supplier |
4. Klik **Simpan**

---

## 7. Tips & Trik

- 📅 **Cek setiap pagi** daftar pesanan yang baru diapprove
- 📊 **Gunakan Laporan Batch** untuk pembelian yang lebih efisien
- 💰 **Bandingkan harga** dari beberapa supplier sebelum membeli
- 📝 **Catat harga aktual** dengan tepat untuk akurasi laporan keuangan
- 🔔 **Prioritaskan** pesanan dengan tanggal event paling dekat

---

## 8. Pertanyaan Umum (FAQ)

**Q: Pesanan sudah Approved tapi tidak muncul di Purchasing?**  
A: Periksa filter bulan. Coba ubah filter atau cari langsung menggunakan nama pelanggan.

**Q: Supplier yang saya ketik tidak tersimpan, bagaimana?**  
A: Ketik nama lengkap supplier dan pastikan menekan Enter atau memilih dari saran dropdown. Sistem akan otomatis mendaftarkan jika nama belum ada.

**Q: Harga aktual berbeda jauh dari estimasi, apakah bermasalah?**  
A: Catat harga aktual yang sesungguhnya. Jika selisihnya sangat besar, informasikan ke Cost Controlling untuk evaluasi.

**Q: Bisa tidak membuat PO untuk pesanan yang masih Pending?**  
A: Tidak. Purchasing hanya bisa dilakukan untuk pesanan yang sudah berstatus **"Approved"** (sudah diverifikasi CC).

**Q: Bagaimana jika bahan baku yang dibutuhkan tidak tersedia dari supplier manapun?**  
A: Segera informasikan ke supervisor dan Cost Controlling. Mungkin perlu penyesuaian menu atau pencarian supplier alternatif.

---

*Terakhir diperbarui: Juni 2026*
