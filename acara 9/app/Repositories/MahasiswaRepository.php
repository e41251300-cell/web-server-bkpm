<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa;
use PDO;

/**
 * Repository menerima objek Database lewat constructor (constructor injection).
 * Semua query memakai PDO prepared statement.
 */
class MahasiswaRepository
{
    private Database $db;

    private const SELECT_JOIN =
        'SELECT m.*, p.nama AS nama_prodi
         FROM mahasiswa m
         LEFT JOIN prodi p ON m.prodi_id = p.id';

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    private function pdo(): PDO
    {
        return $this->db->getConnection();
    }

    /** @return Mahasiswa[] */
    public function all(): array
    {
        $rows = $this->pdo()->query(self::SELECT_JOIN . ' ORDER BY m.id DESC')->fetchAll();
        return array_map([Mahasiswa::class, 'fromRow'], $rows);
    }

    /** @return Mahasiswa[] */
    public function search(string $keyword): array
    {
        $sql = self::SELECT_JOIN . '
                WHERE m.nama LIKE :kw_nama OR m.nim LIKE :kw_nim
                ORDER BY m.id DESC';

        $like = '%' . $keyword . '%';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['kw_nama' => $like, 'kw_nim' => $like]);

        return array_map([Mahasiswa::class, 'fromRow'], $stmt->fetchAll());
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo()->prepare(self::SELECT_JOIN . ' WHERE m.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? Mahasiswa::fromRow($row) : null;
    }

    public function create(Mahasiswa $m): bool
    {
        $stmt = $this->pdo()->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );

        return $stmt->execute([
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi_id' => $m->getProdiId(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
        ]);
    }

    public function update(Mahasiswa $m): bool
    {
        $stmt = $this->pdo()->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id'
        );

        return $stmt->execute([
            'id'       => $m->getId(),
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi_id' => $m->getProdiId(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo()->prepare('DELETE FROM mahasiswa WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /** Untuk dropdown Program Studi. */
    public function prodiOptions(): array
    {
        return $this->pdo()->query('SELECT id, nama FROM prodi ORDER BY nama ASC')->fetchAll();
    }
}
