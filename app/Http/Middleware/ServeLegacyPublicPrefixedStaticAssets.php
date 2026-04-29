<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Blade templates use asset('public/...') while the web root is already /public,
 * so the browser requests /public/assets/... which does not exist on disk as
 * public/public/... . Those requests fall through to Laravel, 404, and the
 * error page can cascade into a 500. Map /public/* to real files under public/.
 */
class ServeLegacyPublicPrefixedStaticAssets
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = $request->path();
        if (strpos($path, 'public/') !== 0) {
            return $next($request);
        }

        $relative = substr($path, strlen('public/'));
        if ($relative === '' || strpos($relative, '..') !== false) {
            return $next($request);
        }

        $full = public_path($relative);
        if (!is_file($full)) {
            return $next($request);
        }

        return new BinaryFileResponse($full, 200, [], true, null, false, true);
    }
}
