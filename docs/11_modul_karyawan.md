# 11 — Modul Karyawan

## Deskripsi

Modul **Manajemen Karyawan** (Employes) adalah HRIS sederhana di dalam sistem. Modul ini mencatat data demografis karyawan, kualifikasi edukasi, data anggota keluarga, kontak darurat darurat, hingga riwayat kontrak kerja.

Selain itu, modul ini juga terintegrasi langsung dengan manajemen Akun Pengguna (`users` table). Jika karyawan membutuhkan akses sistem, pembuatan user bisa dilakukan dalam satu form yang sama.

---

## Komponen

| Komponen | File |
|----------|------|
| Web Controller | `app/Http/Controllers/EmployesController.php` |
| API Controller | `app/Http/Controllers/Api/EmployesApiController.php` |
| Repository | `app/Repository/EmployesRepository.php` |
| Models | `Employes`, `EmployeeEducations`, `EmployeeFamilies`, `EmployeeEmergencies`, `EmployeeContracts`, `User` |

---

## Struktur Data (Models)

Modul ini memiliki struktur data satu-ke-banyak yang kompleks:

```
employes (Master Karyawan)
├── id (ULID, PK)
├── user_id (FK → users.id)  ← Terkait akun login aplikasi
├── name, phone, address, national_id (NIK)
├── status (tetap/kontrak/magang)
├── division (jabatan)
└── timestamps + soft delete

    ↓ (One-to-One)
    ├── employee_contracts (Data Kontrak & Wawancara)
    │     ├── contract_end (date)
    │     └── interview_result (text)

    ↓ (One-to-Many)
    ├── employee_educations (Riwayat Pendidikan)
    │     └── level, school_name, city, major, year_start, graduated
    │
    ├── employee_families (Data Keluarga)
    │     └── name, relation, birth_place_date, gender, education
    │
    └── employee_emergencies (Kontak Darurat)
          └── name, relation, address, phone
```

---

## Alur Kerja Simpan & Perbarui (Upsert)

Karena sifat datanya berjenjang (Nested Data), `EmployesRepository` menggunakan operasi *Upsert* (Update or Insert) dari Laravel untuk menangani relasi One-to-Many secara efisien.

### Pembuatan Karyawan Baru (`store`)

1. Data form dikirim.
2. Jika payload berisi `username`, `email`, dan `password`, sistem membuat entri baru di tabel `users`.
3. Tabel `employes` disimpan (dengan `user_id` yang baru dibuat).
4. Relasi kontrak (`EmployeeContracts`) di-insert.
5. Array data edukasi, keluarga, dan emergency (jika dikirim dan nilainya tidak kosong) di-filter, disisipkan UUID-nya, lalu dilakukan bulk `insert`.

### Pembaruan Data Karyawan (`update`)

1. Modifikasi tabel `users`:
   - Jika akun login sudah ada, di-update.
   - Pengecekan keamanan: Jika nilai password di-form adalah template default Laravel/Env, maka password di database tidak akan ditimpa.
2. Update record `employes`.
3. Update/create (`updateOrCreate`) pada `EmployeeContracts`.
4. Operasi Array Kompleks (`upsert`) untuk riwayat:
   - Data array yang tidak punya `id` valid akan di-_generate_ ULID baru.
   - Dilakukan metode `upsert($data, ['id', 'employee_id'], $fieldsToUpdate)`: Data lama akan di-update, data ULID baru akan di-insert.
   - *Catatan teknis:* Baris data yang dihapus dari UI frontend saat edit idealnya membutuhkan sinkronisasi agar terhapus dari database (penanganan _orphan records_ biasanya dengan mendeteksi ID yang tidak dikirim dan men-delete-nya, namun dalam repository ini menggunakan Upsert untuk mempertahankan jejak).

---

## Format Response API (Detail)

Saat mengakses endpoint `GET /web/employes/{refId}`, repository me-load semua relasi bersarang:

```json
{
  "id": "employee-ulid",
  "name": "Budi Santoso",
  "national_id": "320123456789",
  "division": "Kitchen",
  "status": "tetap",
  "users": {
    "id": 5,
    "name": "budisantoso",
    "email": "budi@lilacatering.com"
  },
  "educations": [
    {
      "id": "edu-ulid-1",
      "education_level": "SMA",
      "school_name": "SMAN 1 Bandung"
    }
  ],
  "families": [
    {
      "id": "fam-ulid-1",
      "relation": "Istri",
      "name": "Siti"
    }
  ],
  "emergencies": [
    {
      "id": "emg-ulid-1",
      "phone": "08123123123"
    }
  ]
}
```
