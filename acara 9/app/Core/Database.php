<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Class Database: bertugas mengelola koneksi PDO.
 * Diberikan ke Repository lewat constructor (Dependency Injection).
 */
class Database
{
    private array $config;
    private ?PDO $pdo = null;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $c = $this->config;
            $dsn = "mysql:host={$c['host']};dbname={$c['dbname']};charset={$c['charset']}";

            try {
                $this->pdo = new PDO($dsn, $c['username'], $c['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die('Koneksi Database Gagal: ' . $e->getMessage());
            }
        }

        return $this->pdo;
    }
}
