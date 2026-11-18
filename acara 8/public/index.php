<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative_class = substr($class, strlen($prefix));
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\MahasiswaController;
use App\Core\Database;
use App\Repositories\MahasiswaRepository;

// ---- Rakit dependency: Database -> Repository -> Controller ----
$database = new Database(require __DIR__ . '/../config/database.php');
$mahasiswaRepo = new MahasiswaRepository($database);

$router = require __DIR__ . '/../routes/web.php';

$router->setResolver(function (string $class) use ($mahasiswaRepo) {
    if ($class === MahasiswaController::class) {
        return new MahasiswaController($mahasiswaRepo); // constructor injection
    }
    return new $class();
});

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
