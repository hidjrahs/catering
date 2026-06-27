# 📚 Dokumentasi Sistem Manajemen Catering

> Dokumentasi teknis lengkap untuk aplikasi **Lila Catering** — Sistem Manajemen Katering berbasis Laravel 12.

---

## 🗂️ Daftar Dokumen

| No | File | Deskripsi |
|----|------|-----------|
| 1 | [01_overview.md](./01_overview.md) | Overview sistem, arsitektur, dan tech stack |
| 2 | [02_arsitektur.md](./02_arsitektur.md) | Arsitektur aplikasi, pola desain, dan struktur folder |
| 3 | [03_database.md](./03_database.md) | Skema database, relasi antar tabel, dan migrasi |
| 4 | [04_autentikasi_dan_otorisasi.md](./04_autentikasi_dan_otorisasi.md) | Autentikasi, role, permission, dan middleware |
| 5 | [05_modul_customer_service.md](./05_modul_customer_service.md) | Modul Customer Service — manajemen pesanan |
| 6 | [06_modul_cost_controlling.md](./06_modul_cost_controlling.md) | Modul Cost Controlling — verifikasi biaya |
| 7 | [07_modul_kitchen.md](./07_modul_kitchen.md) | Modul Kitchen — operasional dapur |
| 8 | [08_modul_purchasing.md](./08_modul_purchasing.md) | Modul Purchasing — manajemen pembelian |
| 9 | [09_modul_menu_catering.md](./09_modul_menu_catering.md) | Modul Menu Catering — manajemen menu |
| 10 | [10_modul_master_data.md](./10_modul_master_data.md) | Modul Master Data (Pelanggan, Supplier, Bahan Baku, dll) |
| 11 | [11_modul_karyawan.md](./11_modul_karyawan.md) | Modul Manajemen Karyawan |
| 12 | [12_modul_stok.md](./12_modul_stok.md) | Modul Manajemen Stok |
| 13 | [13_api_reference.md](./13_api_reference.md) | Referensi API endpoint (web JSON API) |
| 14 | [14_queue_dan_jobs.md](./14_queue_dan_jobs.md) | Queue, Background Jobs, dan Scheduler |
| 15 | [15_deploy_dan_infrastruktur.md](./15_deploy_dan_infrastruktur.md) | Panduan deployment dan infrastruktur Docker |

---

## 🚀 Quick Start

```bash
# Clone & Install
composer install && npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Migrasi & Seed database
php artisan migrate
php artisan db:seed

# Jalankan server (semua sekaligus)
composer run dev
```

Akses aplikasi di: **http://localhost:8008**

---

## 🏢 Tentang Sistem

**Lila Catering** adalah sistem manajemen katering terintegrasi yang mencakup:

- 🛎️ Manajemen pesanan pelanggan (Customer Service)
- 💰 Pengendalian biaya & estimasi harga (Cost Controlling)
- 🍳 Operasional dapur & resep (Kitchen)
- 🛒 Manajemen pembelian bahan baku (Purchasing)
- 📋 Manajemen menu & paket katering
- 📦 Manajemen stok bahan baku
- 👥 Manajemen karyawan
- 📊 Ekspor laporan PDF & Excel

---

*Dokumentasi ini dibuat otomatis — terakhir diperbarui: Juni 2026*
