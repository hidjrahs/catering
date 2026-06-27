# Project Knowledge

This file gives Codebuff context about the project: goals, commands, conventions, and gotchas.

## Quickstart
- **Install:** `composer install && npm install`
- **Dev (all-in-one):** `composer dev` — runs `artisan serve`, `queue:listen`, `pail`, and `vite` concurrently
- **Dev (manual):**
  - `php artisan serve` — HTTP server (port 8000)
  - `npm run dev` — Vite dev server (HMR)
  - `php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5` — queue worker
- **Test:** `php artisan test` or `./vendor/bin/phpunit`
- **Lint/Format:** `./vendor/bin/pint` (Laravel Pint)
- **Build:** `npm run build` (Vite production build)
- **Migrate:** `php artisan migrate`
- **Seed:** `php artisan db:seed` (or specific: `php artisan db:seed --class=IngredientsWithSupplierSeeder`)

## Architecture
- **Stack:** Laravel 12 / PHP 8.2+ / MySQL / Vite + Tailwind CSS 4
- **App name:** Catering management system (catering.id)
- **Language:** Indonesian (id) with English (en) fallback
- **Timezone:** UTC (server), Asia/Jakarta (Docker container)
- **Queue:** `import_temp` queue for recipe/import jobs via `database` driver

### Key directories
- `app/Http/Controllers/` — Web controllers (blade-driven pages)
- `app/Http/Controllers/Api/` — API controllers (JSON responses, prefixed `web/`)
- `app/Repository/` — Repository pattern for data access (one repo per domain)
- `app/Models/` — Eloquent models (plural names: `Customers`, `Employes`, `Ingredients`, etc.)
- `app/Traits/` — Shared traits: `Blameable`, `BlameableCD`, `BlameableWithTicket`, `FormatParse`, `HasUuid`, `IconComponent`, `Validators`
- `app/Jobs/` — Queue jobs: `GenerateImportExcel`, `GenerateRecipe`
- `app/Imports/` — Maatwebsite Excel import: `RecipeMenuImport`
- `app/Exports/` — Maatwebsite Excel export: `RecipeFormatExport`
- `app/Console/Commands/` — Artisan commands: `ClearOldReports`, `PurgeOldSoftDeletes`
- `resources/views/` — Blade templates
- `resources/css/app.css` — Tailwind CSS entry
- `resources/js/app.js` — JS entry
- `database/migrations/` — 40+ migration files
- `database/seeders/` — Seeders for all domains
- `routes/web.php` — Web routes + internal JSON API routes under `web/` prefix
- `routes/api.php` — Minimal external API (auth-protected)

### Domain modules
- **Customer Services** — Order management, export to PDF/Excel
- **Cost Controling** — Cost verification, export reports (PDF/Excel)
- **Kitchen** — Kitchen operations, export
- **Purchasing** — Purchase orders, batch reports
- **Management Stok** — Stock/inventory tracking
- **Menus Catering** — Menu creation, recipe generation, batch import
- **Packet Menus** — Menu packages/bundles
- **Category Menus** — Menu categorization
- **Ingredients** — Ingredient management, supplier linkage
- **Suppliers** — Supplier directory
- **Customers** — Customer directory with address (province/city/district/village)
- **Employes** — Employee management (contracts, education, family, emergencies)
- **Ref Wilayah** — Reference data: provinces, cities, districts, villages
- **Cost Structure** — Cost structure configuration
- **Rincian Biaya** — Cost breakdowns

## Notable Packages
- `barryvdh/laravel-dompdf` — PDF generation
- `maatwebsite/excel` — Excel import/export
- `intervention/image` — Image manipulation
- `simplesoftwareio/simple-qrcode` — QR code generation
- `spatie/laravel-activitylog` — User activity logging
- `spatie/laravel-backup` — Database/file backups
- `spatie/laravel-honeypot` — Anti-spam (honeypot form fields)
- `spatie/laravel-permission` — Roles & permissions
- `staudenmeir/laravel-cte` — Common Table Expressions
- `yajra/laravel-datatables-oracle` — Server-side DataTables
- `sentry/sentry-laravel` — Error monitoring (self-hosted via Docker)
- `doctrine/dbal` — Database abstraction (needed for migration column changes)
- `laravel-lang/publisher` — Language file publishing

## Conventions
- **Models** use plural/English-mixed names (e.g., `Customers`, `Employes`, `MenusCatering`, `PurchasesItems`)
- **Repository pattern** — Each domain has a `Repository` class in `app/Repository/`; controllers delegate data logic to repos
- **Blameable traits** — Use `Blameable`, `BlameableCD`, or `BlameableWithTicket` on models to auto-track created_by/updated_by
- **UUID primary keys** — Some models use `HasUuid` trait for UUID-based IDs
- **Route structure** — Web routes in `routes/web.php` are wrapped in `vpn.restrict` and `auth:web` middleware. Internal API routes are nested under `web/` prefix with `webjson` middleware
- **API naming** — API route groups use dot-notation names (e.g., `web.customers.all-paginate`)
- **CSV route naming** — Some route groups have a `Route::get` for CSV export at `export/{refId}` and `export_rincian/{refId}`
- **Frontend** — Blade templates with Tailwind CSS 4 and Vite. Client-side JS in `resources/js/` and `public/js/`
- **Formatting** — Use Laravel Pint (`./vendor/bin/pint`) for PHP formatting
- **Localization** — Indonesian (id) primary, English (en) fallback. Lang files in `lang/id/` and `lang/en/`

## Gotchas & Constraints
- **VPN restriction:** Routes are protected by `RestrictVpnAccess` middleware — only accessible from specific IP ranges (configured via `VPN_SUBNET` env var, default `127.0.0.`)
- **Honeypot:** Anti-spam honeypot is configurable via `HONEYPOT_ENABLED` and `HONEYPOT_MIN_TIME` env vars
- **Queue required:** Import and recipe generation jobs run on the `import_temp` queue — must have a queue worker running
- **Force logout is ON:** Users can be force-logged out (likely via permission system)
- **Docker deployment:** Multi-stage Dockerfile (PHP 8.4-cli → PHP 8.4-apache), supervisor manages Apache + cron + 2 queue workers
- **Cron job:** Runs `php artisan schedule:run` every minute
- **Storage:** Production storage is mounted at `~/storage_catering` → `/var/www/html/storage/app`
- **Environment:** `.env.example` is the template; copy to `.env` and set `APP_KEY`
- **Logging:** Default log channel is `daily`
- **Session:** Database-driven sessions
- **Cache:** Database-driven cache
