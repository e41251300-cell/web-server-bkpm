<?php
namespace App\Controllers;

class AuthController 
{
    public function loginForm(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header('Location: /si_akademik/acara%207/public/dashboard');
            exit;
        }

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!empty($username) && !empty($password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = ucfirst($username);
            
            $_SESSION['flash_message'] = "Selamat datang, " . ucfirst($username);

            header('Location: /si_akademik/acara%207/public/dashboard');
            exit;
        } else {
            $_SESSION['error'] = "Username dan password wajib diisi!";
            header('Location: /si_akademik/acara%207/public/login');
            exit;
        }
    }

    public function logout(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();

        session_start();
        $_SESSION['flash_message'] = "Anda telah logout";

        header('Location: /si_akademik/acara%207/public/login');
        exit;
    }
}