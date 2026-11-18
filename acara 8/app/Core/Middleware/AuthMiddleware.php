<?php
namespace App\Core\Middleware;

use App\Core\Url;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu.';
            Url::redirect('/login');
        }
    }
}
