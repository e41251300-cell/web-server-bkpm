<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        echo '<h1>Selamat datang di ' . APP_NAME . '</h1>';
        echo '<ul>';
        echo '<li><a href="' . url('/mahasiswa') . '">Daftar mahasiswa</a></li>';
        echo '<li><a href="' . url('/mahasiswa/create') . '">Tambah mahasiswa</a></li>';
        echo '<li><a href="' . url('/mahasiswa/5') . '">Detail mahasiswa 5 (tugas mandiri)</a></li>';
        echo '<li><a href="' . url('/login') . '">Login</a></li>';
        echo '<li><a href="' . url('/halaman-tidak-ada') . '">URL yang tidak ada (uji 404)</a></li>';
        echo '</ul>';
    }
}
