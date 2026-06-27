# 🍳 Panduan Pengguna — Tim Dapur (Kitchen)

**Role:** *(Tidak memerlukan role khusus, akses diberikan oleh Admin)*  
**Akses URL Utama:** `http://localhost:8008/kitchen`

---

## Deskripsi Peran

Sebagai **Tim Dapur (Kitchen)**, Anda menggunakan sistem untuk:

- Melihat daftar pesanan yang perlu dimasak
- Mengetahui detail menu dan bahan baku yang dibutuhkan
- Mencetak **Tugas Dapur** sebagai panduan memasak

> 📌 **Modul Kitchen bersifat READ-ONLY.**  
> Tim dapur hanya bisa melihat dan mencetak data, tidak bisa menambah, mengubah, atau menghapus data apapun.

---

## Navigasi Menu Kitchen

Dari sidebar, akses menu **"Kitchen"**.

---

## Alur Kerja Harian

```
Login ke sistem
      ↓
Buka menu Kitchen
      ↓
Lihat daftar pesanan bulan ini
      ↓
Buka detail pesanan (berdasarkan tanggal event terdekat)
      ↓
Pelajari daftar menu & bahan baku
      ↓
Cetak PDF "Tugas Dapur"
      ↓
Masak sesuai resep & panduan
```

---

## 1. Melihat Daftar Pesanan

1. Login ke sistem
2. Klik menu **"Kitchen"** di sidebar
3. Tabel daftar pesanan akan tampil

### Informasi di Tabel Daftar

| Kolom | Keterangan |
|-------|------------|
| No. Tiket | Nomor identifikasi pesanan |
| Tanggal Event | Kapan acara berlangsung |
| Tanggal Antar | Kapan makanan harus diantar |
| Catatan | Catatan khusus dari Customer Service |
| Jumlah Menu | Berapa jenis menu yang harus dimasak |
| Tanggal Dibuat | Kapan pesanan ini dibuat |

### Filter Daftar

| Filter | Cara Menggunakan |
|--------|-----------------|
| **Cari** | Ketik nama pelanggan |
| **Filter Bulan** | Pilih bulan (default: bulan ini) |
| **Urutan** | Tampilkan berdasarkan tanggal event atau tanggal buat |

> 💡 **Tips:** Gunakan filter **"Urutan berdasarkan Tanggal Event"** untuk memprioritaskan event yang paling dekat.

---

## 2. Melihat Detail Pesanan

Klik pada baris pesanan atau ikon 👁️ untuk membuka detail.

### Informasi Detail yang Ditampilkan

#### Panel Informasi Event

| Info | Keterangan |
|------|------------|
| No. Tiket | Nomor pesanan |
| Tanggal Event | Tanggal dan waktu acara |
| Tanggal Antar | Kapan makanan harus siap diantar |
| Venue | Lokasi event |
| Jumlah Tamu | Berapa porsi yang perlu disiapkan |
| Catatan Khusus | Instruksi spesial dari CS (alergi, vegetarian, dll) |

#### Panel Daftar Menu & Resep

Setiap menu ditampilkan dengan detailnya:

```
📋 [Kategori Menu]
   └── 🍽️ Nama Menu              (Qty: 200 porsi)
         Bahan Baku:
         ├── Beras              → 100 kg
         ├── Garam secukupnya   → (label bebas)
         └── Minyak goreng      → 10 liter
```

#### Jenis Bahan Baku

| Jenis | Tampilan | Keterangan |
|-------|----------|------------|
| **Bahan Terstruktur** | Ada nama + jumlah + satuan | Bahan yang terukur, misal: "Beras 100 kg" |
| **Label Bebas** | Hanya teks deskriptif | Petunjuk tambahan, misal: "Garam secukupnya" |

---

## 3. Mencetak PDF Tugas Dapur

1. Buka detail pesanan
2. Klik tombol **"Export Tugas Dapur"** (ikon 📄)
3. PDF akan diunduh/dibuka di browser
4. Cetak dokumen tersebut

### Isi PDF Tugas Dapur

PDF berisi:

- **Header:** Informasi event (nomor tiket, tanggal, venue, jumlah undangan)
- **Per Menu:**
  - Nama menu
  - Jumlah porsi yang harus dimasak
  - Daftar bahan baku:
    - Nama bahan
    - Jumlah yang dibutuhkan
    - Satuan (kg, liter, gram, dll)
  - Petunjuk tambahan (label bebas)
- **Catatan Khusus** dari Customer Service

---

## 4. Memahami Satuan Bahan Baku

Sistem menggunakan dua jenis satuan:

| Satuan | Keterangan | Contoh |
|--------|------------|--------|
| **Unit (Satuan Beli)** | Satuan saat membeli dari supplier | kg, liter, ikat |
| **Satuan (Satuan Dapur)** | Satuan saat memasak di dapur | gram, ml, lembar |

> ⚠️ Perhatikan satuan yang tertera di resep. Biasanya satuan dapur lebih kecil dari satuan beli.

---

## 5. Panduan Harian Tim Dapur

### Pagi Hari (H-1 atau Pagi Sebelum Event)

1. Login ke sistem
2. Buka Kitchen → cari pesanan hari ini/besok
3. Cetak PDF Tugas Dapur untuk setiap pesanan
4. Distribusikan tugas memasak ke anggota tim
5. Siapkan bahan-bahan sesuai daftar

### Saat Memasak

- Ikuti resep sesuai PDF Tugas Dapur
- Perhatikan catatan khusus pelanggan
- Jika ada kekurangan bahan, segera lapor ke Supervisor/Purchasing

### Setelah Memasak

- Arsipkan PDF Tugas Dapur untuk dokumentasi
- Laporkan kendala (jika ada) ke Supervisor

---

## 6. Tips & Trik

- 📅 **Cek setiap hari** pesanan yang akan datang minggu ini
- 🖨️ **Cetak PDF** untuk setiap event, jangan hanya mengandalkan layar
- ⚠️ **Perhatikan catatan khusus** — alergi atau permintaan khusus harus diikuti dengan ketat
- 📞 **Koordinasi dengan CS** jika ada ketidaksesuaian antara pesanan dan yang tersedia

---

## 7. Pertanyaan Umum (FAQ)

**Q: Ada menu di daftar tapi saya tidak tahu resepnya?**  
A: Sistem menampilkan resep berdasarkan data yang dimasukkan admin. Jika resep tidak lengkap atau ada kesalahan, hubungi administrator atau supervisor.

**Q: Jumlah bahan baku di sistem berbeda dengan yang biasa dimasak?**  
A: Jumlah di sistem dihitung berdasarkan resep standar × jumlah porsi. Jika ada selisih signifikan, koordinasikan dengan Cost Controlling.

**Q: Saya tidak bisa login atau tidak bisa akses menu Kitchen?**  
A: Hubungi administrator untuk memastikan akun Anda sudah dikonfigurasi dengan benar.

**Q: Pesanan sudah approved tapi tidak muncul di Kitchen?**  
A: Kemungkinan filter bulan tidak sesuai. Coba ubah filter bulan atau gunakan fitur pencarian.

---

*Terakhir diperbarui: Juni 2026*
