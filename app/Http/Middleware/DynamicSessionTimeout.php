<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DynamicSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //override the cache with the portal settings
        $lifetime = Cache::remember('portal_settings_session_timeout', now()->addDay(), function () {
            return DB::table('portal_settings')
                ->where('key', 'session_timeout')
                ->value('value');
        });

        // Fallback to .env config
        $lifetime = $lifetime ? (int) $lifetime : config('session.lifetime');

        config(['session.lifetime' => $lifetime]);

        return $next($request);
    }
}
