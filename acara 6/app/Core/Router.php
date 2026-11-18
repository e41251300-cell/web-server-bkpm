<?php
namespace App\Core;

class Router 
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler, array $middleware = []): void 
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => $path,
            'controller' => $handler[0],
            'action'     => $handler[1],
            'middleware' => $middleware
        ];
    }

    public function dispatch(string $uri, string $method): void 
    {
        // 1. Decode URL (%20 jadi spasi) dan ambil path-nya
        $path = parse_url(urldecode($uri), PHP_URL_PATH);

        // 2. Ambil direktori folder public
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);

        // 3. Potong base folder dari path
        if ($baseDir !== '/' && strpos($path, $baseDir) === 0) {
            $path = substr($path, strlen($baseDir));
        }

        // 4. Pastikan path selalu diawali '/'
        if (empty($path) || $path === false) {
            $path = '/';
        }

        // 5. Hilangkan trailing slash di akhir jika ada
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        // 6. Cek Route
        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === strtoupper($method)) {
                
                // Eksekusi Middleware (Instruksi BKPM)[cite: 4]
                $middleware = $route['middleware'] ?? [];
                foreach ($middleware as $mw) {
                    $mwInstance = new $mw();
                    $mwInstance->handle();
                }

                // Eksekusi Controller
                $controller = new $route['controller']();
                $action = $route['action'];
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo "404 - Halaman Tidak Ditemukan";
    }
}