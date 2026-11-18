<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    /** @var callable|null  fungsi pembuat controller (untuk Dependency Injection) */
    private $resolver = null;

    /** Atur cara membuat objek controller, supaya dependency bisa disuntikkan. */
    public function setResolver(callable $resolver): void
    {
        $this->resolver = $resolver;
    }

    public function add(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'path'       => $path,
            'controller' => $handler[0],
            'action'     => $handler[1],
            'middleware' => $middleware,
        ];
    }

    private function make(string $class): object
    {
        return $this->resolver ? ($this->resolver)($class) : new $class();
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = rawurldecode((string) parse_url($uri, PHP_URL_PATH));

        // buang prefix folder (/si_akademik/acara 9/public) dari path
        $baseDir = Url::basePath();
        if ($baseDir !== '' && strpos($path, $baseDir) === 0) {
            $path = substr($path, strlen($baseDir));
        }

        // /index.php/login  ->  /login
        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, strlen('/index.php'));
        }

        if ($path === '' || $path === false) {
            $path = '/';
        }
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['path'] === $path && $route['method'] === strtoupper($method)) {
                foreach ($route['middleware'] as $mw) {
                    (new $mw())->handle();
                }

                $controller = $this->make($route['controller']);
                $action = $route['action'];
                $controller->$action();
                return;
            }
        }

        http_response_code(404);
        echo '404 - Halaman Tidak Ditemukan';
    }
}
