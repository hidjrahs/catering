# 15 — Deploy dan Infrastruktur (Docker)

## Lingkungan Pengembangan (Development)

Proses _development_ dan _testing_ lokal diatur secara fleksibel melalui dua mekanisme utama:

### Metode Standar
Sistem menggabungkan _web-server_, _queue-listener_, log-tailing (`pail`), dan vite frontend ke dalam satu perintah berbasis Node (concurrently):
```bash
composer run dev
```

### Metode Alternatif (Windows / Script Manual)
Bila ingin server HTTP terekspos ke port spesifik (misal di port 4449):
```bash
php -S localhost:4449 -t public
```

---

## Infrastruktur Produksi (Docker)

Sistem telah memiliki _blueprint_ kontainerisasi di dalam direktori `docker-init/`.

### Dockerfile (Multi-stage)

Aplikasi dibangun dari base image `php:8.4-cli` dan beralih ke `php:8.4-apache`.

1. **Persiapan Ekstensi**: Memasang _extensions_ PHP fundamental seperti `pdo_mysql`, `gd` (untuk library Intervention/Image), `zip` (untuk Excel/Maatwebsite), serta _client_ Redis & OPcache.
2. **Setup Apache**: Memodifikasi Vhost `000-default.conf` untuk mengarahkan _DocumentRoot_ ke direktori `/var/www/html/public`. Mengaktifkan `mod_rewrite`.
3. **Supervisor**: Laravel membutuhkan `Queue Worker` yang terus hidup. Di lingkungan Docker, `supervisord` diinstal untuk menjaga hidup tiga proses secara paralel dalam 1 kontainer:
   - Apache (Web Server HTTP)
   - Cron Daemon (untuk `artisan schedule:run`)
   - 2x Worker (proses `queue:listen --queue=import_temp`)

### Konfigurasi Storage Server

Laporan PDF hasil export dan Upload gambar disimpan di folder lokal _storage_ Laravel. 
Dalam setting Docker _Production_, volume _storage_ ini wajib di-mount (diikat) keluar dari kontainer untuk mencegah kehilangan data jika kontainer mati:
```yaml
# Pada docker-compose.yml
volumes:
  - ~/storage_catering:/var/www/html/storage/app
```

---

## Deployment Checklist (Manual / Non-Docker)

Bila instalasi _deployment_ dilakukan manual di server cPanel/VPS Linux biasa:

1. **Persyaratan PHP**: Pastikan PHP versi 8.2+ terinstal beserta _ext-zip_, _ext-gd_, _ext-pdo_, _ext-xml_.
2. **Install Depedensi**: 
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```
3. **Env File**: Sesuaikan `.env` dan pastikan `APP_ENV=production` & `APP_DEBUG=false`.
4. **Symlink Storage**:
   ```bash
   php artisan storage:link
   ```
5. **Konfigurasi Folder Log & Storage**: Hak akses harus memadai untuk Web User (misal: `www-data` atau `nginx`).
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
6. **Background Supervisor**: Konfigurasikan file `.conf` baru di `/etc/supervisor/conf.d/` pada server linux Anda yang mengarah ke `php artisan queue:listen`.
7. **Cronjob**: Atur cron tab server:
   ```bash
   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
   ```
