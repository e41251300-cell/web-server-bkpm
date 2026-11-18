<?php
// Peta URL -> [Controller, method]. {id} = parameter angka pada URL.
return [
    'GET' => [
        '/'                      => ['HomeController', 'index'],
        '/mahasiswa'             => ['MahasiswaController', 'index'],
        '/mahasiswa/create'      => ['MahasiswaController', 'create'],
        '/mahasiswa/{id}'        => ['MahasiswaController', 'show'],
        '/mahasiswa/{id}/edit'   => ['MahasiswaController', 'edit'],
        '/login'                 => ['AuthController', 'loginForm'],
    ],
    'POST' => [
        '/mahasiswa'             => ['MahasiswaController', 'store'],
        '/login'                 => ['AuthController', 'login'],
    ],
];
