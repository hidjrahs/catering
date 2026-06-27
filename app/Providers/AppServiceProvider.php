<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        Request::setTrustedProxies(
            ['*'],
            SymfonyRequest::HEADER_X_FORWARDED_FOR
            | SymfonyRequest::HEADER_X_FORWARDED_HOST
            | SymfonyRequest::HEADER_X_FORWARDED_PORT
            | SymfonyRequest::HEADER_X_FORWARDED_PROTO
        );

        $request = $this->app['request'];
        if ($request->hasHeader('X-Forwarded-Proto')) {
            $request->server->set('HTTPS', str_contains($request->header('X-Forwarded-Proto'), 'https'));
        }

        // Jangan gunakan getPort() karena di production bisa jadi Nginx proxy ke 8000
        $isLocalDev = in_array($request->getHost(), ['127.0.0.1', 'localhost']) && $this->app->environment('local');
        
        $shouldForceHttps = config('app.redirect_https') == true
            || config('app.redirect_https') === 'true'
            || $this->app->environment('production')
            || $request->isSecure()
            || str_starts_with(config('app.url'), 'https://')
            || !$isLocalDev; // Force HTTPS if it's not a known local dev environment

        if ($shouldForceHttps) {
            $url->forceScheme('https');
            URL::forceScheme('https');
            
            // Paksa root URL ke domain production agar tidak bergantung pada file .env atau cache
            $url->forceRootUrl('https://catering.kulodamelsae.com');
            URL::forceRootUrl('https://catering.kulodamelsae.com');
        }

        // Schema::defaultStringLength(191);
    }
}
