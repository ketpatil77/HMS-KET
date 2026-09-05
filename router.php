<?php
// Router for PHP's built-in server on Railway.
// Serve real static files directly; send application routes to CodeIgniter.
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false;
}

require __DIR__ . '/index.php';
