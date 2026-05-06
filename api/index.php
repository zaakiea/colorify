<?php

// 1. Buat struktur folder /tmp yang dibutuhkan agar tidak error
$tmpDirs = [
    '/tmp/storage', 
    '/tmp/storage/logs', 
    '/tmp/storage/framework/views', 
    '/tmp/storage/framework/sessions', 
    '/tmp/storage/framework/cache'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Paksa Laravel menggunakan /tmp untuk semua cache menggunakan putenv dan $_ENV
$envVars = [
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE'   => '/tmp/config.php',
    'APP_ROUTES_CACHE'   => '/tmp/routes.php',
    'APP_EVENTS_CACHE'   => '/tmp/events.php',
];

foreach ($envVars as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// 3. Lanjutkan request ke Laravel
require __DIR__ . '/../public/index.php';
