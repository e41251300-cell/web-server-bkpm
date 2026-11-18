<?php
namespace App\Controllers;

use App\Models\MahasiswaModel;

class MahasiswaController 
{
    public function index(): void 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Panggil Model
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswa = $mahasiswaModel->all();

        // Oper data ke View
        require_once __DIR__ . '/../Views/mahasiswa/index.php';
    }
}