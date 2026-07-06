<?php

namespace Dakshraman\BrowserGuard\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\HttpFoundation\Response;

class BrowserGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (config('browser-guard.no_cache_headers', true)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        if (config('browser-guard.enabled', true)) {
            $this->injectScript($response);
        }

        return $response;
    }

    protected function injectScript(Response $response): void
    {
        $content = $response->getContent();

        if (! is_string($content)) {
            return;
        }

        if (! str_contains($content, '<html')) {
            return;
        }

        $script = view('browser-guard::script')->render();

        if (str_contains($content, 'browser-guard.js') || str_contains($content, 'BrowserGuardConfig')) {
            return;
        }

        if (preg_match('/<\/body>/i', $content)) {
            $content = preg_replace('/<\/body>/i', $script . "\n</body>", $content, 1);
        } else {
            $content .= $script;
        }

        $response->setContent($content);
    }
}
