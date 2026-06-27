
App Library
- Laravel Sentry [Self Hosted : Required setting Docker] [sentry/sentry-laravel]
- Laravel Lang [Multi leanguage] [laravel-lang/publisher]
- Logging Channel : Daily [Default] 
- Laravel Backup [Config Server Storage] [spatie/laravel-backup]
- HoneyPot [spatie/laravel-honeypot]
- ActifityLog User [spatie/laravel-activitylog]
- Force Logout [on]
- Laravel Permission [spatie/laravel-permission]
- Laravel Common Table Expressions (CTE) [staudenmeir/laravel-cte]
- Datatable [yajra/laravel-datatables-oracle]
- RestrictVpnAccess Routes


php artisan queue:listen --queue=import_temp --timeout=0 --sleep=5

php artisan migrate --path=database/migrations/2026_01_01_195513_create_ingredients_supplier.php
php artisan migrate --path=database/migrations/2026_01_01_200025_alter_category_menus_catering.php
php artisan migrate --path=database/migrations/2026_01_10_151124_alter_orders.php
php artisan migrate --path=database/migrations/2026_05_02_165825_alter_order_items.php
php artisan migrate --path=database/migrations/2026_05_09_171634_create_rincian_biaya.php

php artisan db:seed --class=IngredientsWithSupplierSeeder

ngrok http 4449    

http://weepingly-pseudochylous-jama.ngrok-free.dev/

- menu user
- menu management akses