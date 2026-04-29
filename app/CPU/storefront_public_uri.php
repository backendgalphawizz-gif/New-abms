<?php

/**
 * Global helper (views call storefront_public_uri() from the global namespace).
 * Root-relative path for files under Laravel's public/ directory (legacy templates used asset('public/...')).
 * Always resolves on the current browser host, so tenant subdomains are not broken by ASSET_URL or URL forcing.
 */
if (!function_exists('storefront_public_uri')) {
    function storefront_public_uri(string $path): string
    {
        $path = ltrim($path, '/');
        if (strpos($path, 'public/') !== 0) {
            $path = 'public/'.$path;
        }

        return '/'.$path;
    }
}
