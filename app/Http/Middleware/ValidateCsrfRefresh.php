<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateCsrfRefresh
{
    public function handle(Request $request, Closure $next)
    {
        // 1) Hanya izinkan AJAX / X-Requested-With (opsional tapi mengurangi abuse)
        if ($request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // 2) Pastikan Origin/Referer same-origin (lebih aman daripada mengandalkan Origin saja)
        $appUrl = rtrim(config('app.url'), '/');
        $origin = $request->headers->get('origin');
        $referer = $request->headers->get('referer');

        $isSameOrigin = false;
        if ($origin && str_starts_with($origin, $appUrl)) $isSameOrigin = true;
        if ($referer && str_starts_with($referer, $appUrl)) $isSameOrigin = true;

        if (! $isSameOrigin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // 3) Pastikan client mengirim cookie session (laravel_session / app_session)
        $cookieName = config('session.cookie'); // biasanya 'laravel_session' atau 'app_session'
        if (! $request->cookies->has($cookieName)) {
            return response()->json(['message' => 'Session missing'], 403);
        }

        // Lanjut
        return $next($request);
    }
}
