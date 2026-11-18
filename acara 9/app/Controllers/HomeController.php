<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        // Proteksi login sudah ditangani AuthMiddleware pada routes/web.php
        require __DIR__ . '/../Views/dashboard.php';
    }
}
