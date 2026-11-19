
<?php
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Mengambil semua data mahasiswa
    public function getAll(): array {
        return $this->db
            ->query('SELECT * FROM mahasiswa ORDER BY id DESC')
            ->fetchAll();
    }

    // Mengambil data mahasiswa berdasarkan ID
    public function find(int $id) {
        $stmt = $this->db->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        return $stmt->fetch();
    }

    // Mengecek apakah NIM sudah digunakan
    public function nimExists(string $nim, ?int $excludeId = null): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
        }

        $stmt = $this->db->prepare($sql);

        $params = [':nim' => $nim];

        if ($excludeId !== null) {
            $params[':id'] = $excludeId;
        }

        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Menambahkan data mahasiswa
    public function create(array $data): string {
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi)
             VALUES (:nim, :nama, :prodi)'
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi']
        ]);

        return $this->db->lastInsertId();
    }

    // Memperbarui data mahasiswa
    public function update(int $id, array $data): void {
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi = :prodi
             WHERE id = :id'
        );

        $stmt->execute([
            ':nim' => $data['nim'],
            ':nama' => $data['nama'],
            ':prodi' => $data['prodi'],
            ':id' => $id
        ]);
    }

    // Menghapus data mahasiswa
    public function delete(int $id): void {
        $stmt = $this->db->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);
    }
}
