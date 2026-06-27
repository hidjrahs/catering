# 14 — Queue, Background Jobs, dan Scheduler

## Deskripsi

Untuk menangani proses-proses berat yang berisiko memperlambat response server, Lila Catering System menggunakan fitur **Laravel Queue** dan **Scheduler**. 

---

## Konfigurasi Queue

Sistem menggunakan database sebagai penyimpan antrian (*driver*).
- **Tabel**: `jobs` dan `failed_jobs`.
- **Driver**: Terkonfigurasi pada `.env` dengan `QUEUE_CONNECTION=database`.
- **Worker Command Utama**: 
  ```bash
  php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5
  ```

---

## Daftar Background Jobs

### 1. Job: `GenerateImportExcel`
Berfungsi memproses pengunduhan (download) template Excel *Recipe/Menu*. Karena referensi data Master Ingredients sangat banyak, pembuatannya dilakukan secara asinkron (di-background) agar memori PHP-FPM tidak _timeout_.

### 2. Job: `GenerateRecipe`
Dijalankan otomatis setelah pengguna mengunggah (upload) File Excel berisi ratusan/ribuan baris resep.
- **Tugas**: Membaca data staging di tabel `temp_recipe_menu` dan `temp_ingredients_menu`.
- **Logika**: Memetakan kategori menu, memasukkan data `menus_catering`, menyalin komposisi bahan baku dari temporary, hingga mengakhiri dengan update status `import_berkas` menjadi sukses.
- **Queue Line**: Berjalan di antrian `import_temp`.

### 3. Queue via Maatwebsite/Excel (`RecipeMenuImport`)
Fungsi parsing dari package Maatwebsite (`app/Imports/RecipeMenuImport.php`) mengimplementasikan *Chunk Reading* dengan queue sehingga pembacaan ribuan baris file XLSX tidak menghabiskan RAM memori seketika.

---

## Scheduler (Cron)

Sistem Laravel perlu memicu cron tab pada server produksi untuk mengeksekusi *commands* berbasis waktu.

### Setup (Crontab Server)
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Perintah (Commands) Terjadwal

Terdapat _Command Custom_ (`app/Console/Commands`) yang dijadwalkan secara periodik:

1. **`ClearOldReports`**
   - Membersihkan file-file PDF Laporan lama di direktori `storage/report_order/`, `report_purchasing/`, `report_kitchen/`, dan `report_cost_control/` yang umurnya melebihi batas (misal: lebih dari 30 hari). Menghindari kepenuhan disk (Disk Full).
   
2. **`PurgeOldSoftDeletes`**
   - Secara permanen menghapus data (Hard Delete) dari Database untuk tabel-tabel utama yang status *soft-delete*-nya (kolom `deleted_at`) sudah sangat lama. Hal ini membantu meringankan beban optimasi _indexing_ database.
