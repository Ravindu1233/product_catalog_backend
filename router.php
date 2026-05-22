<?php

/**
 * router.php — PHP Built-in Server Router
 *
 * Used ONLY with the PHP built-in development server:
 *   php -S localhost:8000 router.php
 *
 * Responsibilities:
 *  - Serve static files from /public/assets/ when the URI starts with /assets/
 *  - Forward all other requests to index.php (the front controller)
 *
 * Do NOT use this file in production (Apache / Nginx).
 */

$uri = $_SERVER['REQUEST_URI'];

// Strip query string for file-path matching
$path = parse_url($uri, PHP_URL_PATH);

// Map /assets/* → public/assets/*
if (strncmp($path, '/assets/', 8) === 0) {
    $file = __DIR__ . '/public' . $path;

    if (is_file($file)) {
        // Let the built-in server serve the static file automatically
        return false;
    }

    // File not found → 404
    http_response_code(404);
    echo '404 – Asset not found';
    return true;
}

// Everything else → front controller
require_once __DIR__ . '/index.php';
return true;
