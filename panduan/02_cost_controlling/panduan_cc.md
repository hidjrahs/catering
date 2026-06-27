# 💰 Panduan Pengguna — Cost Controlling (CC)

**Role:** `cost_control`  
**Akses URL Utama:** `http://localhost:8008/cost_controling`

> ⚠️ **Perhatian:** Role ini memerlukan koneksi **VPN** untuk dapat mengakses sistem.

---

## Deskripsi Peran

Sebagai **Cost Controlling**, Anda bertanggung jawab memverifikasi dan menyetujui estimasi biaya setiap pesanan sebelum diproses lebih lanjut. Tugas utama Anda meliputi:

- Meninjau pesanan yang dikirim oleh Customer Service
- Menghitung dan memverifikasi estimasi biaya produksi
- Menyesuaikan porsi menu jika diperlukan
- Menentukan harga jual akhir dan margin keuntungan
- Menyetujui (approve) pesanan yang sudah terverifikasi
- Mencetak laporan cost control dan surat resep untuk dapur

---

## Navigasi Menu CC

Dari sidebar, Anda bisa mengakses:

```
💰 Cost Controlling       → Daftar & verifikasi pesanan
```

---

## Alur Kerja Harian

```
Buka halaman Cost Controlling
           ↓
Lihat daftar pesanan "Pending"
           ↓
Buka detail pesanan
           ↓
Periksa menu & bahan baku dari resep
           ↓
Kalkulasi estimasi biaya produksi
           ↓
Sesuaikan porsi (jika perlu)
           ↓
Pilih template struktur biaya
           ↓
Isi komponen biaya & harga jual
           ↓
Submit verifikasi → status "Approved"
           ↓
Cetak Laporan Cost Control & Surat Resep
```

---

## 1. Membuka Halaman Cost Controlling

1. Login ke sistem
2. Klik menu **"Cost Controlling"** di sidebar
3. Halaman daftar pesanan akan tampil, berisi pesanan yang perlu diverifikasi

### Tampilan Daftar Pesanan

| Kolom | Keterangan |
|-------|------------|
| No. Tiket | Nomor tiket pesanan |
| Pelanggan | Nama pelanggan |
| Tanggal Event | Kapan event berlangsung |
| Status | Status pesanan (focus pada "Pending") |
| Aksi | Tombol detail, export PDF |

### Filter Pesanan

| Filter | Cara Menggunakan |
|--------|-----------------|
| **Cari** | Ketik nama/no telepon pelanggan |
| **Status** | Filter berdasarkan status (Pending, Approved, dll) |
| **Bulan** | Pilih bulan yang ingin ditampilkan |
| **Urutan** | Urutkan berdasarkan tanggal event atau tanggal buat |

> 💡 **Tips:** Fokus pada pesanan berstatus **"Pending"** untuk dikerjakan terlebih dahulu.

---

## 2. Memeriksa Detail Pesanan

1. Klik ikon 👁️ atau nama pesanan untuk membuka detail
2. Halaman detail akan menampilkan:

### Panel Informasi Pesanan

- Nomor tiket, nama pelanggan, tanggal event
- Venue, jumlah tamu, jumlah undangan
- Jenis event dan paket yang dipilih
- Catatan khusus dari Customer Service

### Panel Daftar Menu

Setiap menu yang dipesan ditampilkan beserta:

| Info | Keterangan |
|------|------------|
| Nama Menu | Nama menu yang dipesan |
| Porsi Saat Ini | Jumlah porsi sesuai pesanan CS |
| Harga Per Porsi | Harga satuan per porsi |
| Total Harga | Total harga menu ini |
| Bahan Baku | Daftar bahan baku dari resep menu ini |

### Panel Rincian Biaya

Daftar biaya tambahan yang sudah diinput CS (transport, dekorasi, dll).

---

## 3. Verifikasi & Approve Pesanan

Setelah memeriksa detail, lakukan verifikasi:

### Langkah 1: Sesuaikan Porsi Menu (Jika Diperlukan)

Jika ada penyesuaian porsi:

1. Temukan menu yang perlu disesuaikan
2. Ubah nilai di kolom **"Porsi"** 
3. Sistem otomatis menghitung ulang harga berdasarkan porsi baru
4. Tambahkan catatan penyesuaian di kolom **"Catatan Menu"**

> 💡 **Rumus Harga:** `(Harga Standar ÷ Porsi Standar) × Porsi Baru`  
> Contoh: Harga Standar Rp1.000.000 / 50 porsi = Rp20.000/porsi × 60 porsi = Rp1.200.000

### Langkah 2: Pilih Template Struktur Biaya

1. Di bagian **"Struktur Biaya"**, pilih template yang sesuai dari dropdown
2. Template akan otomatis mengisi komponen biaya (Bahan Baku, Operasional, dll)
3. Sesuaikan nilai prosentase atau harga tetap jika perlu

### Langkah 3: Isi Komponen Biaya

Untuk setiap komponen biaya:

| Tipe | Cara Isi | Keterangan |
|------|----------|------------|
| **Prosentase** | Isi % dari harga jual | Contoh: 60% dari Rp5.000.000 = Rp3.000.000 |
| **Harga Tetap (Fixed)** | Isi nilai nominal | Contoh: Biaya transport tetap Rp500.000 |

### Langkah 4: Tentukan Harga Jual & Kalkulasi Margin

Isi form kalkulasi utama:

| Field | Keterangan | Contoh |
|-------|------------|--------|
| **Estimasi Biaya Produksi** | Total biaya untuk memproduksi | Rp3.000.000 |
| **Harga Jual yang Disetujui** | Harga final yang ditagihkan ke pelanggan | Rp5.000.000 |
| **Margin Keuntungan** | Selisih harga jual - biaya produksi | Rp2.000.000 |

### Langkah 5: Perbarui Rincian Biaya

Jika ada perubahan pada rincian biaya (transport, dekorasi, dll):

1. Temukan item rincian biaya di panel bawah
2. Ubah nama, qty, atau harga sesuai kebutuhan
3. Klik simpan di baris tersebut

### Langkah 6: Submit Verifikasi

1. Periksa kembali semua data
2. Klik tombol **"Verifikasi & Setujui"**
3. Sistem akan:
   - Memperbarui porsi menu di order
   - Memperbarui harga pesanan
   - Mengubah status pesanan dari `pending` → **`approved`**
   - Menyimpan data cost estimation
   - Membuat PDF laporan cost control otomatis

---

## 4. Export PDF

### A. Laporan Cost Control

Berisi:
- Informasi pesanan lengkap
- Daftar menu + porsi yang sudah disetujui
- Estimasi biaya, harga jual, dan margin
- Komponen struktur biaya
- QR Code nomor order

**Cara:** Klik tombol **"Export Cost Control"** pada halaman detail

### B. Surat Resep (SR) — untuk Dapur

Berisi:
- Info event (tanggal, venue, jumlah undangan)
- Setiap menu yang harus dimasak
- Daftar bahan baku + jumlah + satuan per menu

**Cara:** Klik tombol **"Export Surat Resep"** pada halaman detail

> 📌 **Cetak Surat Resep** dan serahkan ke tim dapur setelah pesanan diapprove.

---

## 5. Memahami Struktur Biaya (Cost Structure)

Struktur biaya adalah template yang berisi komponen-komponen pengeluaran standar. Contoh:

| Komponen | Tipe | Nilai |
|----------|------|-------|
| Biaya Bahan Baku | Prosentase | 60% dari harga jual |
| Gaji Crew | Prosentase | 15% dari harga jual |
| Biaya Operasional | Fixed | Rp500.000 |
| Biaya Transport | Fixed | Rp300.000 |

> 💡 Pilih template yang paling sesuai dengan jenis event. Jika tidak ada yang cocok, hubungi administrator untuk menambahkan template baru.

---

## 6. Tips & Trik

- 📊 **Selalu verifikasi margin** — pastikan margin tidak negatif sebelum menyetujui
- 🔍 **Periksa bahan baku** yang dibutuhkan untuk memastikan ketersediaan
- 📝 **Isi catatan verifikasi** untuk dokumentasi keputusan pricing
- ⚡ **Prioritaskan pesanan** dengan tanggal event terdekat

---

## 7. Pertanyaan Umum (FAQ)

**Q: Apakah saya bisa menolak pesanan?**  
A: Sistem tidak memiliki status "ditolak". Jika pesanan tidak bisa dipenuhi, koordinasikan dengan CS untuk membatalkan pesanan (`cancelled`).

**Q: Bagaimana jika harga jual yang diinput CS tidak sesuai?**  
A: Anda bisa mengubah harga jual saat proses verifikasi. Harga akhir yang Anda isi di "Harga Jual yang Disetujui" akan menjadi harga resmi.

**Q: Bisa tidak mengubah pesanan yang sudah Approved?**  
A: Secara sistem tidak diizinkan. Koordinasikan dengan administrator jika ada perubahan pasca-approval.

**Q: Template struktur biaya tidak sesuai, bagaimana?**  
A: Hubungi administrator untuk menambahkan atau mengubah template struktur biaya di menu Master Data → Cost Structure.

---

*Terakhir diperbarui: Juni 2026*
