<?php
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\MahasiswaController;
use App\Core\Middleware\AuthMiddleware;

$router = new Router();

// Route Publik
$router->add('GET', '/login', [AuthController::class, 'loginForm']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);

// Route Terproteksi AuthMiddleware (BKPM)[cite: 5]
$router->add('GET', '/', [HomeController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/dashboard', [HomeController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/mahasiswa', [MahasiswaController::class, 'index'], [AuthMiddleware::class]);

return $router;