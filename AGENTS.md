# AGENTS.md

## Project overview
Laravel 12 catering management system. Indonesian locale (`id`). MySQL for everything (db, sessions, cache, queue).

## Key commands
```bash
composer run dev          # concurrently: artisan serve + queue:listen + pail + vite
php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5
php -S localhost:4449 -t public  # alt dev server (services.bat)
php artisan migrate --path=database/migrations/<file>
php artisan db:seed --class=<Seeder>
php artisan pail            # tail logs
ngrok http 4449             # expose localhost
```
Tests: `php artisan test` or `vendor/bin/phpunit`.

## Architecture
- **Controllers** → **Repository** layer → **Models**. Both web and API controllers exist under `app/Http/Controllers/` and `.../Api/`.
- Web routes in `routes/web.php` (all web UI + JSON API routes under `webjson` middleware).
- API routes in `routes/api.php` (minimal, not primary entrypoint).
- **Repository pattern**: `app/Repository/*Repository.php` contain query/business logic. Controllers delegate to repositories.
- **Models** use UUID primary keys (`HasUuid` trait) and `Blameable` trait.
- **Permissions**: `spatie/laravel-permission` (role-based).
- **Activity log**: `spatie/laravel-activitylog` (all user actions logged).

## Middleware quirks
- `vpn.restrict` — blocks non-VPN IPs for users with VPN-restricted roles (checked via `VPN_SUBNET` env).
- `webjson` — all `/web/*` API routes require `X-Requested-With: XMLHttpRequest` or `Accept: application/json`; otherwise redirects to `/home`.

## Queue
- Default driver: `database` (MySQL table `jobs`).
- Dedicated queue `import_temp` with dedicated listener (used by import jobs).
- 2 supervisor workers for `import_temp` in production Docker.

## Frontend
- Blade templates in `resources/views/` with Tailwind CSS 4 + Vite.
- Entrypoints: `resources/css/app.css`, `resources/js/app.js`.

## Key config
- `.env` requires `DB_DATABASE=catering`, `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`.
- `VPN_SUBNET` controls VPN access restriction (default `127.0.0.`).
- `APP_LOCALE=id`, `APP_FAKER_LOCALE=id_ID`.
- Sentry configured for error tracking.

## Docker
- `docker-init/docker-compose.yml` builds from `Dockerfile`.
- Production uses supervisor to run Apache, cron, and 2 `import_temp` queue workers.
- Storage mounted to `~/storage_catering` on host.

## Testing
- PHPUnit with `tests/Unit/` and `tests/Feature/` suites.
- No SQLite in-memory tests configured (DB tests connect to real MySQL — commented out in `phpunit.xml`).
