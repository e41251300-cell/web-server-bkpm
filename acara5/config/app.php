<?php
define('APP_NAME', 'SI Akademik');

/** Path dasar project, dihitung otomatis dari lokasi public/index.php.
 *  Contoh: "/si_akademik/acara5/public" (tidak perlu ditulis manual). */
function base_path(): string
{
    return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
}

/** Membuat URL yang benar di folder mana pun project berada. */
function url(string $path = '/'): string
{
    return str_replace(' ', '%20', base_path()) . '/' . ltrim($path, '/');
}

/** Redirect (302). exit wajib agar kode di bawahnya tidak jalan. */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function e(?string $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
