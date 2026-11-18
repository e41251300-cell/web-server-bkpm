<?php
namespace App\Controllers;

class MahasiswaController
{
    // Data sementara (belum pakai database)
    private const DATA = [
        1 => ['nim' => 'E41240001', 'nama' => 'Budi Santoso'],
        2 => ['nim' => 'E41240002', 'nama' => 'Siti Aminah'],
        5 => ['nim' => 'E41240005', 'nama' => 'Andi Pratama'],
    ];

    public function index(): void
    {
        // $_GET: data dari query string, contoh /mahasiswa?q=budi
        $q = trim($_GET['q'] ?? '');

        echo '<h1>Daftar Mahasiswa</h1>';
        echo '<form method="get" action="' . url('/mahasiswa') . '">';
        echo '<input type="text" name="q" value="' . e($q) . '" placeholder="Cari nama...">';
        echo ' <button>Cari</button></form>';

        echo '<ul>';
        foreach (self::DATA as $id => $m) {
            if ($q === '' || stripos($m['nama'], $q) !== false) {
                echo '<li><a href="' . url("/mahasiswa/{$id}") . '">' . e($m['nim'] . ' - ' . $m['nama']) . '</a>';
                echo ' | <a href="' . url("/mahasiswa/{$id}/edit") . '">edit</a></li>';
            }
        }
        echo '</ul>';
        echo '<a href="' . url('/mahasiswa/create') . '">+ Tambah</a> | <a href="' . url('/') . '">Beranda</a>';
    }

    public function create(): void
    {
        echo '<h1>Tambah Mahasiswa</h1>';
        echo '<form method="post" action="' . url('/mahasiswa') . '">';
        echo 'NIM: <input type="text" name="nim"><br>';
        echo 'Nama: <input type="text" name="nama"><br>';
        echo '<button>Simpan</button></form>';
    }

    // POST /mahasiswa -> $_POST, lalu redirect
    public function store(): void
    {
        $nim  = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($nim === '' || $nama === '') {
            http_response_code(422);
            echo '<h1>Data tidak lengkap</h1><a href="' . url('/mahasiswa/create') . '">Kembali</a>';
            return;
        }

        // (Nanti: simpan ke database). Setelah sukses -> redirect ke daftar
        redirect('/mahasiswa');
    }

    // Tugas mandiri: /mahasiswa/5 -> show(5)
    public function show(int $id): void
    {
        $m = self::DATA[$id] ?? null;
        if ($m === null) {
            http_response_code(404);
            echo '<h1>404 - Mahasiswa dengan ID ' . $id . ' tidak ditemukan</h1>';
            return;
        }

        echo '<h1>Detail Mahasiswa</h1>';
        echo '<p>ID: ' . $id . '</p>';
        echo '<p>NIM: ' . e($m['nim']) . '</p>';
        echo '<p>Nama: ' . e($m['nama']) . '</p>';
        echo '<a href="' . url('/mahasiswa') . '">Kembali</a>';
    }

    public function edit(int $id): void
    {
        echo '<h1>Edit Mahasiswa ID ' . $id . '</h1>';
        echo '<a href="' . url('/mahasiswa') . '">Kembali</a>';
    }
}
