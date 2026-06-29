<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RestrictVpnAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $restrictedRoles=Role::where(['is_vpn'=>true,'guard_name'=>'web'])->get()->pluck('name')->toArray();
        // dd($request);
        if ($user && $user->hasAnyRole($restrictedRoles)) {
            $ip = $request->ip();
            $vpnSubnet = env('VPN_SUBNET', '127.0.');

            if (app()->environment('local', 'development')) {
                return $next($request);
            }

            if (!str_starts_with($ip, $vpnSubnet)) {
                abort(403, 'Akses hanya bisa dari jaringan kantor (VPN).');
            }
        }
        return $next($request);
    }
}
