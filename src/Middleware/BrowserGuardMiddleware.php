<?php

namespace Dakshraman\BrowserGuard\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrowserGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        view()->share('browserGuardEnabledFromMiddleware', true);

        /** @var Response $response */
        $response = $next($request);

        if (config('browser-guard.no_cache_headers', true)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return $response;
    }
}
