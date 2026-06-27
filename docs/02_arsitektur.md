# 02 — Arsitektur Aplikasi

## Pola Arsitektur

Aplikasi menggunakan pola **MVC + Repository Pattern**:

```
Request → Middleware → Controller → Repository → Model → Database
                                       ↓
                                  Response (JSON / Blade View)
```

### Komponen Utama

| Lapisan | Lokasi | Fungsi |
|---------|--------|--------|
| **Route** | `routes/web.php` | Definisi semua URL dan mapping ke Controller |
| **Middleware** | `app/Http/Middleware/` | Filter request (auth, VPN, JSON) |
| **Controller** | `app/Http/Controllers/` | Menerima request, memanggil Repository, mengembalikan response |
| **API Controller** | `app/Http/Controllers/Api/` | Controller khusus JSON response |
| **Repository** | `app/Repository/` | Logika bisnis & query database |
| **Model** | `app/Models/` | Representasi tabel database + relasi |
| **Trait** | `app/Traits/` | Fitur reusable (audit trail, format parsing) |
| **Resource** | `app/Http/Resources/` | Transformasi data untuk response API |
| **View** | `resources/views/` | Template Blade untuk UI |
| **Job** | `app/Jobs/` | Background tasks (generate Excel/resep) |

---

## Struktur Direktori

```
d:\project\catering\
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── ClearOldReports.php      # Hapus laporan lama
│   │       └── PurgeOldSoftDeletes.php  # Bersihkan data soft-deleted
│   ├── Exports/
│   │   └── RecipeFormatExport.php       # Export format resep ke Excel
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/                     # Controller JSON API
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CustomerServicesApiController.php
│   │   │   │   ├── CostControlingApiController.php
│   │   │   │   ├── KitchenApiController.php
│   │   │   │   ├── PurchasingApiController.php
│   │   │   │   ├── MenuCateringApiController.php
│   │   │   │   ├── ManagementStokApiController.php
│   │   │   │   ├── CustomersApiController.php
│   │   │   │   ├── SuppliersApiController.php
│   │   │   │   ├── IngredientsApiController.php
│   │   │   │   ├── CategoryMenuApiController.php
│   │   │   │   ├── PacketMenuApiController.php
│   │   │   │   ├── EmployesApiController.php
│   │   │   │   ├── RefWilayahApiController.php
│   │   │   │   └── CostStructureApiController.php
│   │   │   ├── CustomerServicesController.php  # Blade pages
│   │   │   ├── CostControlingController.php
│   │   │   ├── KitchenController.php
│   │   │   ├── PurchasingController.php
│   │   │   ├── MenuCateringController.php
│   │   │   ├── ManagementStokController.php
│   │   │   ├── CustomersController.php
│   │   │   ├── SuppliersController.php
│   │   │   ├── IngredientsController.php
│   │   │   ├── CategoryMenuController.php
│   │   │   ├── PacketMenuController.php
│   │   │   ├── EmployesController.php
│   │   │   ├── RefWilayahController.php
│   │   │   ├── HomeController.php
│   │   │   └── LoginController.php
│   │   ├── Middleware/
│   │   │   ├── RestrictVpnAccess.php   # Blokir non-VPN
│   │   │   ├── WebJson.php             # Validasi AJAX/JSON request
│   │   │   ├── ValidateCsrfRefresh.php
│   │   │   └── VerifyCsrfToken.php
│   │   └── Resources/                  # API Resource transformers
│   ├── Imports/
│   │   └── RecipeMenuImport.php        # Import resep dari Excel
│   ├── Jobs/
│   │   ├── GenerateImportExcel.php     # Job generate Excel untuk import
│   │   └── GenerateRecipe.php          # Job generate resep
│   ├── Models/                         # Eloquent models
│   ├── Providers/                      # Service providers
│   ├── Repository/                     # Business logic layer
│   └── Traits/                         # Shared traits
├── database/
│   ├── migrations/                     # 50+ migration files
│   ├── seeders/                        # Data seeder
│   └── factories/                      # Model factories
├── resources/
│   ├── css/app.css                     # Tailwind CSS entrypoint
│   ├── js/app.js                       # JavaScript entrypoint
│   └── views/                          # Blade templates
│       ├── export_pdf/                 # Template PDF
│       └── ...
├── routes/
│   ├── web.php                         # Semua route (web + internal API)
│   ├── api.php                         # External API minimal
│   └── console.php                     # Artisan console routes
└── docker-init/                        # Docker configuration
    └── docker-compose.yml
```

---

## Repository Pattern

Setiap domain memiliki satu file Repository yang berisi logika bisnis:

```php
// Contoh: CustomerServicesController.php
class CustomerServicesController extends Controller
{
    public function index() 
    {
        return view('customer_service');  // Hanya return view
    }
}

// CustomerServicesApiController.php
class CustomerServicesApiController extends Controller
{
    public function index(Request $request)
    {
        // Delegasi ke Repository
        $result = CustomerServicesRepository::getallData($request);
        return $result;
    }

    public function store(Request $request)
    {
        $result = CustomerServicesRepository::store($request);
        return response()->json(['data' => $result]);
    }
}
```

### Mapping Repository → Controller

| Repository | API Controller | Domain |
|------------|---------------|--------|
| `CustomerServicesRepository` | `CustomerServicesApiController` | Pesanan |
| `CostControlingRepository` | `CostControlingApiController` | Biaya |
| `KitchenRepository` | `KitchenApiController` | Dapur |
| `PurchasingRepository` | `PurchasingApiController` | Pembelian |
| `MenuCateringRepository` | `MenuCateringApiController` | Menu |
| `MenuRepository` | — | Menu (simple) |
| `CategoryMenuRepository` | `CategoryMenuApiController` | Kategori Menu |
| `PacketMenuRepository` | `PacketMenuApiController` | Paket Menu |
| `IngredientsRepository` | `IngredientsApiController` | Bahan Baku |
| `SuppliersRepository` | `SuppliersApiController` | Supplier |
| `CustomersRepository` | `CustomersApiController` | Pelanggan |
| `EmployesRepository` | `EmployesApiController` | Karyawan |
| `RefWIlayahRepository` | `RefWilayahApiController` | Wilayah |
| `CostStructureRepository` | `CostStructureApiController` | Struktur Biaya |
| `ManagementStokRepository` | `ManagementStokApiController` | Stok |
| `ImportRepository` | — | Import Data |

---

## Traits Reusable

### `Blameable`
Mencatat `created_by` dan `updated_by` otomatis dari authenticated user.

### `BlameableWithTicket`
Seperti Blameable tetapi juga auto-generate `order_ticket` dengan format:
```
LL-{ymdhi}-{XXX}
Contoh: LL-2601271530-ABC
```
Digunakan pada model `Orders`.

### `FormatParse`
Utilitas parsing angka (quantity, harga) dari format ribuan Indonesia:
- `quantity($val)` — parse string "1.000" → float 1000
- `numberformat($val)` — format angka ke string "1.000"
- `parseQuantity($val)` — alias numberformat

### `IconComponent`
Mapping nama kategori menu ke ikon emoji/SVG untuk tampilan UI.

---

## Pola DataTable

Semua listing data menggunakan **Yajra DataTables** dengan dua mode:
- `device=web` atau `device=stealth` → format HTML + DataTable server-side
- Default (mobile) → format JSON sederhana

```php
// Contoh dalam Repository:
if(in_array(request('device'), ['web','stealth'])) {
    return self::datatableWeb($result);
}
return self::datatableMobile($result);
```
