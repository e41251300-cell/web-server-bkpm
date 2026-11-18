<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader sederhana untuk Namespace App
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load file routing
$router = require_once __DIR__ . '/../routes/web.php';

// Ambil URI dan HTTP Method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Eksekusi Router
$router->dispatch($uri, $method);