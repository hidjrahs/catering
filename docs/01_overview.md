# 01 — Overview Sistem Manajemen Catering

## Deskripsi Umum

**Lila Catering Management System** adalah aplikasi web berbasis Laravel 12 yang dirancang untuk mengelola seluruh operasional bisnis katering — dari penerimaan pesanan pelanggan, estimasi biaya, proses dapur, hingga pembelian bahan baku.

Aplikasi ini menggunakan **bahasa Indonesia** sebagai antarmuka utama dan dioptimalkan untuk penggunaan internal (internal tools) dengan perlindungan VPN.

---

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Framework** | Laravel 12 |
| **Bahasa** | PHP 8.2+ |
| **Database** | MySQL |
| **Frontend** | Blade Templates + Tailwind CSS 4 + Vite |
| **Queue** | Database driver (MySQL `jobs` table) |
| **Session** | Database (`sessions` table) |
| **Cache** | Database |
| **PDF** | barryvdh/laravel-dompdf |
| **Excel** | maatwebsite/excel |
| **QR Code** | simplesoftwareio/simple-qrcode |
| **Gambar** | intervention/image |
| **Monitoring** | Sentry (self-hosted) |

---

## Package Utama

```json
{
  "barryvdh/laravel-dompdf":       "^3.1",
  "intervention/image":            "^3.11",
  "maatwebsite/excel":             "^3.1",
  "simplesoftwareio/simple-qrcode":"^4.2",
  "spatie/laravel-activitylog":    "^4.10",
  "spatie/laravel-backup":         "^9.3",
  "spatie/laravel-permission":     "^6.21",
  "yajra/laravel-datatables-oracle":"^12.4",
  "staudenmeir/laravel-cte":       "^1.12",
  "sentry/sentry-laravel":         "^4.15"
}
```

---

## Konfigurasi Environment Utama

| Variabel | Nilai Default | Keterangan |
|----------|---------------|------------|
| `DB_DATABASE` | `catering` | Nama database MySQL |
| `SESSION_DRIVER` | `database` | Session disimpan di database |
| `CACHE_STORE` | `database` | Cache disimpan di database |
| `QUEUE_CONNECTION` | `database` | Queue menggunakan database |
| `APP_LOCALE` | `id` | Bahasa Indonesia |
| `APP_FAKER_LOCALE` | `id_ID` | Faker dalam bahasa Indonesia |
| `VPN_SUBNET` | `127.0.0.` | Subnet yang diizinkan akses |
| `HONEYPOT_ENABLED` | `true` | Anti-spam aktif |

---

## Modul-Modul Utama

```
┌─────────────────────────────────────────────────────┐
│                  LILA CATERING SYSTEM                │
├─────────────────────────────────────────────────────┤
│  [Customer Service]  →  Kelola Pesanan Pelanggan    │
│  [Cost Controlling]  →  Verifikasi & Estimasi Biaya │
│  [Kitchen]           →  Operasional Dapur           │
│  [Purchasing]        →  Pembelian Bahan Baku        │
│  [Manajemen Stok]    →  Inventory Bahan Baku        │
│  [Menu Catering]     →  Kelola Menu & Resep         │
│  [Paket Menu]        →  Bundling Paket              │
│  [Kategori Menu]     →  Kategorisasi Menu           │
│  [Bahan Baku]        →  Master Bahan Baku           │
│  [Supplier]          →  Master Pemasok              │
│  [Pelanggan]         →  Master Pelanggan            │
│  [Karyawan]          →  Manajemen SDM               │
│  [Ref Wilayah]       →  Provinsi/Kota/Kec/Desa     │
│  [Struktur Biaya]    →  Template Struktur Harga     │
└─────────────────────────────────────────────────────┘
```

---

## Alur Bisnis Utama

```
1. Pelanggan memesan → Customer Service membuat Order
       ↓
2. Order dikirim ke Cost Controlling untuk verifikasi harga
       ↓
3. Setelah disetujui → status "approved"
       ↓
4. Kitchen menerima daftar menu & resep untuk dimasak
       ↓
5. Purchasing membuat Purchase Order untuk bahan baku
       ↓
6. Bahan baku diterima → stok bertambah
       ↓
7. Laporan PDF dikirim ke pelanggan
```

---

## Status Order

| Status | Deskripsi |
|--------|-----------|
| `pending` | Pesanan baru masuk, belum diproses |
| `approved` | Disetujui oleh cost controlling |
| `purchased` | Bahan baku sudah dibeli |
| `cancelled` | Pesanan dibatalkan |

---

## Cara Menjalankan

```bash
# Development (all-in-one)
composer run dev
# Menjalankan: artisan serve + queue:listen + pail + npm run dev

# Alternatif manual:
php artisan serve         # Web server port 8000
npm run dev               # Vite HMR
php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5

# Production (via services.bat):
php -S localhost:4449 -t public
```

---

## Konvensi Kode

| Aspek | Konvensi |
|-------|----------|
| Primary Key | ULID (26 karakter, kronologis) |
| Model | Nama plural: `Customers`, `Employes`, `MenusCatering` |
| Timestamp audit | `created_by`, `updated_by`, `deleted_by` via Blameable trait |
| Soft Delete | Digunakan pada sebagian besar model utama |
| Routing | Web + internal JSON API di `routes/web.php` |
| API prefix | `/web/` dengan middleware `webjson` |
