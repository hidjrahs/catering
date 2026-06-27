# 📋 Panduan Pengguna — Customer Service (CS)

**Role:** `customer_service`  
**Akses URL Utama:** `http://localhost:8008/customer_service`

---

## Deskripsi Peran

Sebagai **Customer Service**, Anda adalah garda terdepan yang menerima pesanan dari pelanggan dan memasukkannya ke dalam sistem. Anda bertanggung jawab atas:

- Menerima pesanan telepon/langsung dari pelanggan
- Memasukkan data pesanan ke sistem
- Memastikan informasi pesanan akurat dan lengkap
- Mencetak laporan pesanan (PDF) untuk pelanggan
- Memantau status pesanan yang sudah dibuat

---

## Navigasi Menu CS

Dari sidebar, Anda bisa mengakses:

```
📋 Customer Service       → Daftar & kelola pesanan
📦 Data Master
   └── Data Customer      → Lihat/cari data pelanggan
```

---

## Alur Kerja Harian

```
Terima Pesanan (telepon/langsung)
          ↓
Buka halaman Customer Service
          ↓
Tambah Pesanan Baru
          ↓
Isi form pesanan lengkap
          ↓
Simpan → PDF otomatis tergenerate
          ↓
Cetak/kirimkan PDF ke pelanggan
          ↓
Pantau status pesanan (pending → approved → purchased)
```

---

## 1. Membuka Halaman Customer Service

1. Login ke sistem
2. Klik menu **"Customer Service"** di sidebar kiri
3. Halaman daftar pesanan akan tampil

### Tampilan Daftar Pesanan

Tabel menampilkan kolom-kolom berikut:

| Kolom | Keterangan |
|-------|------------|
| No. Tiket | Nomor unik pesanan (format: `LL-YYMMDDHHMM-XXX`) |
| Nama Pelanggan | Nama pelanggan |
| Tanggal Event | Kapan event berlangsung |
| Tanggal Antar | Kapan makanan diantar |
| Jumlah Tamu | Total tamu yang makan |
| Status | Status pesanan saat ini |
| Aksi | Tombol edit, hapus, export PDF |

### Filter & Pencarian

| Filter | Cara Menggunakan |
|--------|-----------------|
| **Cari** | Ketik nama/no telepon pelanggan di kotak pencarian |
| **Filter Bulan** | Pilih bulan dari dropdown (default: bulan ini) |
| **Urutan** | Pilih urut berdasarkan tanggal event atau tanggal dibuat |

---

## 2. Membuat Pesanan Baru

Klik tombol **"+ Tambah Pesanan"** (atau tombol hijau di sudut kanan atas).

### Langkah 1: Cari atau Tambah Pelanggan

Di kolom **"Pelanggan"**:

- **Pelanggan lama:** Ketik nama atau nomor HP → pilih dari saran yang muncul
- **Pelanggan baru:** Ketik nama lengkap pelanggan → sistem otomatis membuat data pelanggan baru

> 💡 Jika pelanggan baru, Anda juga perlu mengisi **nomor HP** dan **alamat** pelanggan.

### Langkah 2: Isi Data Event

| Field | Keterangan | Contoh |
|-------|------------|--------|
| **Tanggal Event** | Kapan acara berlangsung | 14 Februari 2025 |
| **Waktu Mulai Event** | Jam mulai acara | 10:00 |
| **Tanggal & Jam Antar** | Kapan makanan diantar ke lokasi | 14 Feb 2025, 07:00 |
| **Venue / Lokasi** | Tempat acara berlangsung | Gedung Serbaguna Kota |
| **Jumlah Tamu** | Berapa orang yang akan makan | 200 |
| **Jumlah Undangan** | Total undangan yang dikirim | 500 |

### Langkah 3: Pilih Jenis Event & Paket

- **Jenis Event:** Pilih satu atau lebih jenis acara (contoh: Pernikahan, Sunatan, Ulang Tahun)
- **Paket Menu:** Pilih paket yang diinginkan pelanggan (contoh: Paket Gold, Paket Silver)

> ℹ️ Bisa memilih lebih dari satu jenis event dan paket.

### Langkah 4: Pilih Menu yang Dipesan

Di bagian **"Daftar Menu"**:

1. Klik **"+ Tambah Menu"**
2. Cari dan pilih menu dari katalog (ketik nama menu)
3. Sistem akan otomatis mengisi **harga** berdasarkan jumlah tamu
4. Untuk menu yang bisa dikurangi/ditambah porsinya, isi kolom **"Jumlah Porsi"**
5. Tambahkan **catatan** khusus untuk menu tertentu (contoh: "Tidak pedas")

> 💡 **Cara Hitung Harga Menu:**  
> Harga = (Harga Standar ÷ Porsi Standar) × Jumlah Tamu  
> *Contoh: Menu Nasi Putih — Harga Standar Rp500.000 / 100 porsi = Rp5.000/porsi × 200 tamu = Rp1.000.000*

### Langkah 5: Isi Rincian Biaya Tambahan

Di bagian **"Rincian Biaya"** (transport, dekorasi, dll):

1. Klik **"+ Tambah Rincian"**
2. Isi **Nama Biaya** (contoh: Biaya Transport, Sewa Tenda)
3. Isi **Jumlah (Qty)** dan **Harga**
4. Ulangi untuk setiap item biaya tambahan

### Langkah 6: Isi Uang Muka (DP)

- Isi kolom **"Down Payment / Uang Muka"** jika pelanggan membayar DP
- Format: angka tanpa titik/koma (contoh: `1000000` untuk Rp1.000.000)

### Langkah 7: Tambahkan Catatan

| Field | Keterangan |
|-------|------------|
| **Catatan Umum** | Instruksi umum untuk dapur/pengiriman |
| **Catatan Khusus** | Permintaan khusus pelanggan (alergi, menu halal, dll) |

### Langkah 8: Simpan Pesanan

1. Periksa kembali semua data yang telah diisi
2. Klik tombol **"Simpan"**
3. Sistem akan:
   - Membuat nomor tiket pesanan otomatis
   - Mengatur status pesanan menjadi **"Pending"**
   - **Membuat PDF laporan pesanan secara otomatis**
4. Unduh atau cetak PDF tersebut untuk pelanggan

---

## 3. Mengubah Pesanan

> ⚠️ **Catatan:** Pesanan hanya bisa diubah selama masih berstatus **"Pending"**. Setelah diapprove oleh Cost Controlling, perubahan harus melalui koordinasi dengan tim CC.

1. Cari pesanan di daftar
2. Klik ikon ✏️ **Edit** pada baris pesanan
3. Ubah data yang diperlukan
4. Klik **"Simpan"**

---

## 4. Export PDF Laporan Pesanan

Ada dua jenis laporan PDF yang bisa dicetak:

### A. Laporan Pesanan (untuk Pelanggan)

Berisi:
- Info pesanan (nomor tiket, tanggal, venue)
- Data pelanggan + alamat
- Daftar menu yang dipesan (dikelompokkan per kategori)
- QR Code nomor order

**Cara:** Klik ikon 📄 di kolom Aksi → pilih **"Export Pesanan"**

### B. Laporan Rincian Biaya

Berisi:
- Informasi event
- Rincian biaya (transport, dekorasi, dll)
- Total biaya keseluruhan

**Cara:** Klik ikon 📄 di kolom Aksi → pilih **"Export Rincian Biaya"**

---

## 5. Memantau Status Pesanan

Pantau status pesanan Anda di kolom **"Status"**:

| Status | Artinya | Tindakan CS |
|--------|---------|-------------|
| ⏳ **Pending** | Pesanan baru, belum diverifikasi | Boleh diubah |
| ✅ **Approved** | Disetujui Cost Controlling | Koordinasi jika ada perubahan |
| 🛒 **Purchased** | Bahan baku sudah dibeli | Proses selesai dari sisi CS |
| ❌ **Cancelled** | Dibatalkan | Hubungi supervisor |

---

## 6. Menghapus Pesanan

> ⚠️ **Hati-hati!** Penghapusan pesanan bersifat permanen (soft delete). Pastikan sudah mendapat persetujuan supervisor.

1. Centang pesanan yang ingin dihapus
2. Klik tombol **"Hapus Terpilih"**
3. Konfirmasi penghapusan

---

## 7. Tips & Trik

- 🔍 **Gunakan fitur pencarian** untuk menemukan pesanan pelanggan lama dengan cepat
- 📅 **Filter berdasarkan bulan event** untuk memantau pesanan mendatang
- 📋 **Selalu cetak PDF** dan simpan salinannya untuk arsip
- 📞 **Catat catatan khusus** dengan detail agar dapur bisa mempersiapkan dengan tepat

---

## 8. Pertanyaan Umum (FAQ)

**Q: Pelanggan saya tidak ada di sistem, harus bagaimana?**  
A: Ketik nama pelanggan baru di kolom Pelanggan. Sistem akan otomatis membuat data pelanggan baru saat pesanan disimpan. Pastikan Anda mengisi nomor HP dan alamat.

**Q: Bisa tidak mengubah pesanan yang sudah Approved?**  
A: Tidak bisa langsung. Anda perlu berkoordinasi dengan tim Cost Controlling untuk melakukan penyesuaian.

**Q: Bagaimana jika pelanggan membatalkan pesanan?**  
A: Hubungi supervisor untuk persetujuan pembatalan, kemudian ubah status pesanan menjadi "Cancelled".

**Q: PDF tidak bisa diunduh?**  
A: Pastikan koneksi internet/intranet stabil. Jika masih gagal, hubungi administrator.

---

*Terakhir diperbarui: Juni 2026*
