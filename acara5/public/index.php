<?php
// Front controller: SEMUA request masuk lewat file ini (berkat .htaccess)
require_once __DIR__ . '/../config/app.php';

// Autoload sederhana: App\Controllers\X -> app/Controllers/X.php
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$routes = require __DIR__ . '/../routes/web.php';

// 1. Ambil URL yang diminta (buang base path & query string)
$uri  = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$base = base_path();
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . trim($uri, '/');

// 2. Ambil method (GET/POST)
$method = $_SERVER['REQUEST_METHOD'];

// 3. Cari di array routes (mendukung parameter {id})
foreach ($routes[$method] ?? [] as $pattern => [$controllerName, $action]) {
    $regex = '#^' . preg_replace('#\{id\}#', '(?P<id>\d+)', $pattern) . '$#';

    if (preg_match($regex, $uri, $matches)) {
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // 4. Ketemu -> panggil Controller@method
        $class      = "App\\Controllers\\{$controllerName}";
        $controller = new $class();
        $controller->$action(...array_values($params));
        exit;
    }
}

// 5. Tidak ketemu -> 404
http_response_code(404);
echo '<h1>404 - Halaman Tidak Ditemukan</h1>';
echo '<p>URL <b>' . e($uri) . '</b> tidak tersedia.</p>';
echo '<p><a href="' . url('/') . '">Kembali ke beranda</a></p>';
