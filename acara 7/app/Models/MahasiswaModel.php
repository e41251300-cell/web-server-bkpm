<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class MahasiswaModel 
{
    private PDO $db;

    public function __construct() 
    {
        $this->db = Database::getConnection();
    }

    public function all(): array 
    {
        // Join ke tabel prodi untuk mengambil nama program studi
        $query = "SELECT mahasiswa.*, prodi.nama AS nama_prodi 
                  FROM mahasiswa 
                  LEFT JOIN prodi ON mahasiswa.prodi_id = prodi.id 
                  ORDER BY mahasiswa.id ASC";

        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
}