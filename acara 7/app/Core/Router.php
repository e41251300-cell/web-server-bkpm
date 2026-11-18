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
        $path = parse_url(urldecode($uri), PHP_URL_PATH);
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);

        if ($baseDir !== '/' && strpos($path, $baseDir) === 0) {
            $path = substr($path, strlen($baseDir));
        }

        if (empty($path) || $path === false) {
            $path = '/';
        }

        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === strtoupper($method)) {
                $middleware = $route['middleware'] ?? [];
                foreach ($middleware as $mw) {
                    $mwInstance = new $mw();
                    $mwInstance->handle();
                }

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