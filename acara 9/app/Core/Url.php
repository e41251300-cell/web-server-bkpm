<?php
namespace App\Core;

/**
 * Helper URL dinamis.
 * Base URL dihitung otomatis dari lokasi public/index.php,
 * jadi folder boleh di-copy/rename (acara 9, acara 10, dst.) tanpa edit link manual.
 */
class Url
{
    /** Path folder public, versi asli (tanpa encoding). Contoh: /si_akademik/acara 9/public */
    public static function basePath(): string
    {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $dir = rtrim($dir, '/');
        return ($dir === '.' ? '' : $dir);
    }

    /** Base URL yang aman dipakai di browser (spasi -> %20). */
    public static function base(): string
    {
        $path = self::basePath();
        if ($path === '') {
            return '';
        }
        $segments = array_map('rawurlencode', explode('/', ltrim($path, '/')));
        return '/' . implode('/', $segments);
    }

    /** Contoh: Url::to('/mahasiswa/edit?id=3') */
    public static function to(string $path = ''): string
    {
        return self::base() . '/' . ltrim($path, '/');
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . self::to($path));
        exit;
    }
}
