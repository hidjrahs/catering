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
            ['127.0.0.1', '::1'],
            SymfonyRequest::HEADER_X_FORWARDED_FOR
            | SymfonyRequest::HEADER_X_FORWARDED_HOST
            | SymfonyRequest::HEADER_X_FORWARDED_PORT
            | SymfonyRequest::HEADER_X_FORWARDED_PROTO
        );

        $request = $this->app['request'];
        if ($request->hasHeader('X-Forwarded-Proto')) {
            $request->server->set('HTTPS', $request->header('X-Forwarded-Proto') === 'https');
        }

        $shouldForceHttps = config('app.redirect_https') == true
            || config('app.redirect_https') === 'true'
            || $this->app->environment('production')
            || $request->isSecure();

        if ($shouldForceHttps) {
            $url->forceScheme('https');
            URL::forceScheme('https');
        }

        // Schema::defaultStringLength(191);
    }
}
