<?php
namespace App\Controllers;

class AuthController
{
    public function loginForm(): void
    {
        echo '<h1>Login</h1>';
        echo '<form method="post" action="' . url('/login') . '">';
        echo 'Username: <input type="text" name="username"><br>';
        echo 'Password: <input type="password" name="password"><br>';
        echo '<button>Masuk</button></form>';
    }

    public function login(): void
    {
        // Proses login sebenarnya dikerjakan pada acara berikutnya
        redirect('/');
    }
}
