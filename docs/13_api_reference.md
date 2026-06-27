# 13 — API Reference

## Konvensi Internal API

Semua operasi CRUD data yang dipanggil oleh antarmuka frontend (Blade/AJAX) dilewatkan melalui internal API.

### Prefix & Middleware

- **Prefix Route:** `/web/...`
- **Middleware Utama:** `webjson`
  Middleware ini memeriksa _headers_ HTTP request. Request akan ditolak dan dialihkan ke `/home` jika bukan request JSON (AJAX).
  
  Syarat *Header* Request:
  ```http
  X-Requested-With: XMLHttpRequest
  // ATAU
  Accept: application/json
  ```

---

## Pola URL Standar

Sistem menggunakan pola API yang konsisten untuk seluruh modul Master Data dan Transaksional:

| Method | Pattern URL | Nama Route | Keterangan |
|--------|-------------|------------|------------|
| `GET` | `/web/{module}` | `web.{module}.index` | List Data (DataTables) |
| `POST` | `/web/{module}` | `web.{module}.store` | Simpan Data Baru |
| `GET` | `/web/{module}/select`| `web.{module}.select`| Dropdown Pencarian |
| `GET` | `/web/{module}/{id}` | `web.{module}.show` | Detail Data (Spesifik) |
| `PUT` | `/web/{module}/{id}` | `web.{module}.update`| Ubah Data |
| `POST` | `/web/{module}/deletes`| `web.{module}.destroy`| Hapus Massal (Bulk) |

*Contoh: `{module}` = `customers`, `menus_catering`, `suppliers`.*

---

## Standar Response Payload

Aplikasi mematuhi standar response `Illuminate\Http\JsonResponse`.

### Berhasil (Success: 200 OK)
```json
{
    "data": { ...objek return dari repository... }
}
```

### Response Validasi Gagal (422 Unprocessable Entity)
Digunakan ketika data wajib tidak dikirim atau salah tipe data. Ditangani otomatis oleh `Validator` atau Request Form Laravel.
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "email": ["Email format is invalid"]
    }
}
```

### Custom Exception Server (500 Internal Server Error)
Jika terdapat logika bisnis di dalam `Repository` yang mendeteksi kesalahan berdasar rule spesifik, Repository akan melemparkan exception:
```php
throw new Exception('Data Customer tidak ditemukan.', 404);
```
Sistem akan membalas dengan status 404/500 JSON.

---

## Endpoint Response Yajra DataTables

Endpoint GET utama (`/web/{module}`) selalu menghasilkan output yang menyesuaikan dengan parameter query `device`.

### A. Device: Web/Stealth (`device=web`)
Digunakan oleh tabel server-side di tampilan antarmuka Admin (Blade). Data dirender sebagian ke format tag HTML (contoh: status dipakaikan *badges/span*).

**Parameter Request (Otomatis dari library jQuery DataTable):**
- `draw`: int
- `start`: int
- `length`: int
- `search[value]`: string
- `order[0][column]`: int
- `device`: "web"

**Response Format:**
```json
{
  "draw": 1,
  "recordsTotal": 120,
  "recordsFiltered": 5,
  "data": [
    {
       "DT_RowIndex": 1,
       "id": "01JMMXYZ...",
       "name": "Budi",
       "created_at": "<span class='text-muted fs-7'>27/01/2026 12:00:00</span>"
    }
  ]
}
```

### B. Device: Mobile / Default (`device=mobile` atau kosong)
Berfungsi mengirimkan raw JSON array tanpa tag HTML. Cocok jika suatu saat di-consume oleh App Mobile atau front-end framework lain seperti Vue/React.

**Response Format:**
```json
{
  "draw": 1,
  "recordsTotal": 120,
  "recordsFiltered": 120,
  "data": [
    {
       "DT_RowIndex": 1,
       "id": "01JMMXYZ...",
       "name": "Budi",
       "created_at": "27/01/2026 12:00:00"
    }
  ]
}
```
