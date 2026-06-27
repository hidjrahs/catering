# 📚 Panduan Penggunaan — Lila Catering Management System

Folder ini berisi panduan penggunaan lengkap aplikasi **Lila Catering Management System** yang disusun berdasarkan peran (role) masing-masing pengguna.

---

## 🗂️ Struktur Panduan

```
panduan/
├── README.md                          ← Halaman ini
│
├── 00_login_dan_umum/
│   └── panduan_login.md               ← Login, logout, navigasi umum
│
├── 01_customer_service/
│   ├── panduan_cs.md                  ← Panduan lengkap Customer Service
│   └── checklist_pesanan_baru.md      ← Checklist membuat pesanan baru
│
├── 02_cost_controlling/
│   ├── panduan_cc.md                  ← Panduan lengkap Cost Controlling
│   └── checklist_verifikasi.md        ← Checklist verifikasi pesanan
│
├── 03_kitchen/
│   └── panduan_kitchen.md             ← Panduan tim dapur
│
├── 04_purchasing/
│   ├── panduan_purchasing.md          ← Panduan lengkap Purchasing
│   └── checklist_po.md                ← Checklist Purchase Order
│
└── 05_admin_super_admin/
    └── panduan_admin.md               ← Panduan Admin & Super Admin
```

---

## 👥 Daftar Role Pengguna

| Role | Nama Tampilan | Deskripsi Singkat |
|------|--------------|-------------------|
| `super_admin` | Super Admin | Akses penuh ke seluruh sistem |
| `admin` | Admin | Kelola master data & pengaturan |
| `customer_service` | Customer Service (CS) | Input & kelola pesanan pelanggan |
| `cost_control` | Cost Controlling (CC) | Verifikasi & kalkulasi biaya pesanan |
| `purchasing` | Purchasing | Pembelian bahan baku |
| *(tidak ada role)* | Kitchen | Melihat tugas dapur (read-only) |

---

## 🔄 Alur Bisnis Utama

```
[Pelanggan Memesan]
       ↓
[Customer Service] → Membuat pesanan (status: pending)
       ↓
[Cost Controlling] → Memverifikasi biaya (status: approved)
       ↓
[Kitchen]          → Melihat resep & mencetak tugas dapur
       ↓
[Purchasing]       → Membuat PO bahan baku (status: purchased)
```

---

## ℹ️ Informasi Teknis

- Akses aplikasi melalui browser di: `http://localhost:8008`
- Seluruh pengguna harus login terlebih dahulu
- Beberapa role memerlukan koneksi VPN untuk dapat mengakses sistem
- Sesi login otomatis berakhir setelah **120 menit** tidak aktif
