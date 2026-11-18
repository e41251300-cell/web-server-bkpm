<?php
namespace App\Controllers;

use App\Core\Url;

class AuthController
{
    public function loginForm(): void
    {
        if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            Url::redirect('/dashboard');
        }

        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['error'] = 'Username dan password wajib diisi!';
            Url::redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['logged_in'] = true;
        $_SESSION['user_name'] = ucfirst($username);
        $_SESSION['flash_message'] = 'Selamat datang, ' . ucfirst($username);

        Url::redirect('/dashboard');
    }

    public function logout(): void
    {
        session_destroy();
        session_start();
        $_SESSION['flash_message'] = 'Anda telah logout';

        Url::redirect('/login');
    }
}
