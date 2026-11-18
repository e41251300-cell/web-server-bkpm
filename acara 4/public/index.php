<?php

require_once __DIR__ . '/../app/Models/Mahasiswa.php';

use App\Models\Mahasiswa;

$mahasiswa = [
    new Mahasiswa("2501001", "Niha", "Teknik Informatika"),
    new Mahasiswa("2501002", "Natus", "Teknik Informatika"),
    new Mahasiswa("2501003", "Bil", "Teknik Informatika"),
    new Mahasiswa("2467899", "Rin", "Teknik Komputer")
];

$content = __DIR__ . '/../app/Views/mahasiswa/index.php';

require __DIR__ . '/../app/Views/layouts/main.php';