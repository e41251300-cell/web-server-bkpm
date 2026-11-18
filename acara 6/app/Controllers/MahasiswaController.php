<?php
namespace App\Controllers;

class MahasiswaController 
{
    public function index(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }
}