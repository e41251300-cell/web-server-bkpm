<?php
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\MahasiswaController;
use App\Core\Middleware\AuthMiddleware;

$router = new Router();

// Route Public
$router->add('GET', '/login', [AuthController::class, 'loginForm']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);

// Route Protected
$router->add('GET', '/', [HomeController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/dashboard', [HomeController::class, 'index'], [AuthMiddleware::class]);

// Route CRUD Mahasiswa
$router->add('GET', '/mahasiswa', [MahasiswaController::class, 'index'], [AuthMiddleware::class]);
$router->add('GET', '/mahasiswa/create', [MahasiswaController::class, 'create'], [AuthMiddleware::class]);
$router->add('POST', '/mahasiswa/store', [MahasiswaController::class, 'store'], [AuthMiddleware::class]);
$router->add('GET', '/mahasiswa/edit', [MahasiswaController::class, 'edit'], [AuthMiddleware::class]);
$router->add('POST', '/mahasiswa/update', [MahasiswaController::class, 'update'], [AuthMiddleware::class]);
$router->add('POST', '/mahasiswa/delete', [MahasiswaController::class, 'destroy'], [AuthMiddleware::class]);

return $router;