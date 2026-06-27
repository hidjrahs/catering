# 🔐 Panduan Login & Penggunaan Umum

Panduan ini berlaku untuk **semua pengguna** Lila Catering Management System, tanpa terkecuali role apapun.

---

## 1. Mengakses Aplikasi

Buka browser (Chrome / Firefox / Edge) dan ketikkan alamat:

```
http://localhost:8008
```

> **Catatan:** Jika Anda mendapat pesan "Akses Ditolak" atau halaman error, kemungkinan koneksi VPN belum aktif. Hubungi administrator untuk bantuan.

---

## 2. Halaman Login

Setelah membuka aplikasi, Anda akan diarahkan ke halaman **Login**.

### Cara Login

1. Isi kolom **Email** dengan email akun Anda
2. Isi kolom **Password** dengan kata sandi Anda
3. Klik tombol **Login**

![Halaman Login](../assets/login.png)

### Jika Login Gagal

| Pesan Error | Kemungkinan Penyebab | Solusi |
|-------------|---------------------|--------|
| "Kredensial tidak valid" | Email atau password salah | Periksa kembali huruf besar/kecil pada password |
| Halaman tidak bisa diakses | VPN tidak aktif | Aktifkan VPN terlebih dahulu |
| Halaman login tidak muncul | Server mati | Hubungi administrator |

> 💡 **Tips:** Pastikan tombol **Caps Lock** tidak aktif saat mengetikkan password.

---

## 3. Dashboard Utama (Beranda)

Setelah login berhasil, Anda akan diarahkan ke halaman **Dashboard/Beranda** (`/home`).

Dashboard menampilkan:
- **Ringkasan statistik** pesanan (pending, approved, purchased)
- **Menu navigasi** di sidebar kiri sesuai hak akses Anda
- **Notifikasi** atau informasi terkini (jika ada)

### Navigasi Sidebar

Sidebar sebelah kiri berisi menu-menu yang bisa Anda akses sesuai role:

| Bagian | Menu yang Tersedia |
|--------|--------------------|
| **Transaksi** | Customer Service, Cost Controlling, Purchasing, Kitchen |
| **Master** | Data Master (customer, supplier, barang, dll) |
| **Pengaturan** | Daftar User, Role Permission, Profil Usaha *(khusus Admin)* |

> 🔒 Menu yang tidak sesuai dengan role Anda tidak akan ditampilkan.

---

## 4. Profil Pengguna

Untuk melihat atau mengubah profil akun:

1. Klik nama/foto profil di pojok kanan atas
2. Pilih **"Profil"** atau **"Pengaturan Akun"**
3. Ubah nama atau password sesuai kebutuhan
4. Klik **Simpan**

> ⚠️ **Penting:** Jangan bagikan password Anda kepada siapapun, termasuk administrator.

---

## 5. Logout (Keluar)

Selalu lakukan logout setelah selesai menggunakan aplikasi, terutama jika menggunakan komputer bersama.

### Cara Logout

1. Klik nama/foto profil di pojok kanan atas
2. Pilih **"Logout"** atau **"Keluar"**
3. Anda akan diarahkan kembali ke halaman login

> ⏰ **Sesi Otomatis Berakhir:** Jika tidak ada aktivitas selama **120 menit**, sistem akan otomatis mengeluarkan Anda dan meminta login ulang.

---

## 6. Fitur Umum di Seluruh Modul

### Tabel Data (DataTable)

Hampir semua halaman daftar menggunakan tabel interaktif dengan fitur:

| Fitur | Cara Menggunakan |
|-------|-----------------|
| **Pencarian** | Ketik kata kunci di kolom "Cari..." |
| **Urutkan** | Klik judul kolom untuk mengurutkan naik/turun |
| **Filter Bulan** | Pilih bulan dari dropdown filter |
| **Per Halaman** | Pilih jumlah baris yang ditampilkan (10/25/50/100) |

### Tombol Aksi

| Ikon/Tombol | Fungsi |
|-------------|--------|
| ✏️ **Edit** | Membuka form untuk mengubah data |
| 🗑️ **Hapus** | Menghapus data (muncul konfirmasi) |
| 📄 **Export PDF** | Mengunduh laporan dalam format PDF |
| ➕ **Tambah Baru** | Membuka form untuk menambah data baru |

### Notifikasi Status

| Warna | Arti |
|-------|------|
| 🟢 Hijau | Operasi berhasil |
| 🔴 Merah | Terjadi kesalahan |
| 🟡 Kuning | Peringatan / perlu perhatian |
| 🔵 Biru | Informasi |

---

## 7. Status Pesanan

Penting dipahami oleh semua pengguna, status pesanan adalah sebagai berikut:

| Status | Label | Deskripsi |
|--------|-------|-----------|
| `pending` | ⏳ Menunggu | Pesanan baru, belum diverifikasi |
| `approved` | ✅ Disetujui | Telah diverifikasi oleh Cost Controlling |
| `purchased` | 🛒 Dibeli | Bahan baku sudah dibeli oleh Purchasing |
| `cancelled` | ❌ Dibatalkan | Pesanan dibatalkan |

---

## 8. Kontak & Bantuan

Jika mengalami kendala teknis, hubungi:

- **Administrator Sistem** — untuk masalah akun, hak akses, atau error sistem
- **Supervisor/Kepala Divisi** — untuk masalah alur kerja atau prosedur

---

*Terakhir diperbarui: Juni 2026*
