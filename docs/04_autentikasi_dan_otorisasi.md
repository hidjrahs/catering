# 04 — Autentikasi & Otorisasi

## Autentikasi

### Login System

Aplikasi menggunakan **Laravel session-based authentication** dengan guard `web`.

```
URL Login  : GET  /login
URL Process: POST /login  → AuthController@login
URL Logout : GET  /logout → AuthController@logout
```

#### Flow Login

```
1. User buka /login → tampil form login
2. Submit username + password
3. AuthController::login() → Auth::attempt()
4. Jika berhasil → redirect ke /home
5. Jika gagal → kembali ke form dengan pesan error
```

#### AuthController

```php
// File: app/Http/Controllers/Api/AuthController.php

// Login
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('home');
    }
    return back()->withErrors(['email' => 'Kredensial tidak valid']);
}

// Logout
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    return redirect('/login');
}
```

---

## Middleware Keamanan

### 1. `vpn.restrict` — RestrictVpnAccess

**File**: `app/Http/Middleware/RestrictVpnAccess.php`

Memblokir akses dari IP yang tidak berada dalam subnet VPN yang dikonfigurasi.

```php
// .env
VPN_SUBNET=127.0.0.   // Default: localhost only

// Semua route authenticated menggunakan middleware ini:
Route::middleware(['vpn.restrict', 'auth:web'])->group(function () {
    // ...semua halaman sistem
});
```

**Perilaku**:
- Cek IP request terhadap `VPN_SUBNET`
- User dengan role yang memerlukan VPN akan diblokir jika tidak dari subnet VPN
- Default `127.0.0.` → hanya localhost yang bisa akses

### 2. `webjson` — WebJson

**File**: `app/Http/Middleware/WebJson.php`

Memastikan route API internal hanya bisa diakses melalui AJAX/JSON request.

```php
public function handle(Request $request, Closure $next): Response
{
    if (!($request->wantsJson() || $request->ajax())) {
        return redirect()->intended('home');
    }
    return $next($request);
}
```

**Penggunaan**: Semua route di prefix `/web/` menggunakan middleware ini.

```
// Request harus menyertakan salah satu:
X-Requested-With: XMLHttpRequest
Accept: application/json
```

### 3. `auth:web`

Middleware Laravel standar untuk memastikan user sudah login via guard `web`.

---

## Role & Permission

Aplikasi menggunakan **`spatie/laravel-permission`** untuk manajemen role dan permission.

### Setup Role & Permission

```bash
# Seed data role dan permission:
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=UserSeeder
```

### Struktur Tabel

| Tabel | Fungsi |
|-------|--------|
| `permissions` | Daftar hak akses (misal: `customer_service.view`) |
| `roles` | Daftar peran (misal: `admin`, `cs`, `kitchen`) |
| `role_has_permissions` | Mapping role → permission |
| `model_has_roles` | Mapping user → role |

### Penggunaan dalam Kode

```php
// Cek permission:
$user->hasPermissionTo('customer_service.view');

// Cek role:
$user->hasRole('admin');

// Assign role:
$user->assignRole('cs');
```

---

## Route Protection

### Struktur Proteksi Route

```php
// routes/web.php

// Public routes (guest only):
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('web.login');
});

// Protected routes (authenticated + VPN):
Route::middleware(['vpn.restrict', 'auth:web'])->group(function () {
    
    // Blade pages (hanya return view):
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/customer_service', [CustomerServicesController::class, 'index']);
    // ...
    
    // Internal JSON API (tambahan middleware webjson):
    Route::middleware(['webjson'])->group(function () {
        Route::prefix('web')->group(function () {
            // Semua API route di sini
        });
    });
});
```

### Tabel Route Proteksi

| Route | Middleware | Keterangan |
|-------|-----------|------------|
| `/login` | `guest` | Hanya untuk belum login |
| `/home` | `vpn.restrict`, `auth:web` | Dashboard utama |
| `/customer_service` | `vpn.restrict`, `auth:web` | Halaman CS |
| `/web/customer_service/*` | `vpn.restrict`, `auth:web`, `webjson` | API CS |
| `/web/cost_controling/*` | `vpn.restrict`, `auth:web`, `webjson` | API Cost |

---

## Activity Log

Semua aksi CRUD pada model utama dicatat menggunakan **`spatie/laravel-activitylog`**.

### Model yang di-log

| Model | Log Name | Atribut yang Dicatat |
|-------|----------|--------------------|
| `Orders` | `orders` | `customer_id`, `order_date` |
| `Customers` | `customers` | `name`, `phone` |
| `MenusCatering` | `menus_catering` | `name`, `selling_price`, `category_menus_catering_id` |

### Konfigurasi Log

```php
// Di setiap model:
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->useLogName('orders')
        ->logOnly(['customer_id', 'order_date'])
        ->logOnlyDirty()         // Hanya log jika ada perubahan
        ->dontSubmitEmptyLogs(); // Skip log kosong
}

// Kustomisasi deskripsi log:
public function tapActivity(Activity $activity, string $eventName)
{
    switch ($eventName) {
        case 'created': $activity->description = 'created a Order'; break;
        case 'updated': $activity->description = 'updated a Order'; break;
        case 'deleted': $activity->description = 'deleted a Order'; break;
    }
}
```

### Membaca Log

```php
// Query log aktivitas:
use Spatie\Activitylog\Models\Activity;

// Semua log
Activity::all();

// Log per model
Activity::where('log_name', 'orders')->get();

// Log per user
Activity::causedBy($user)->get();
```

---

## Session Management

| Konfigurasi | Nilai |
|-------------|-------|
| Driver | `database` |
| Tabel | `sessions` |
| Lifetime | 120 menit (default Laravel) |
| Regenerasi | Setelah login berhasil |
| Invalidasi | Saat logout |
