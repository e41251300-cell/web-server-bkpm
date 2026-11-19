<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Logger.php';

try {
    // Mengambil koneksi database
    $database = \App\Core\Database::getInstance();
    $connection = $database->getConnection();

    // Memeriksa HTTP Method
    $method = $_SERVER['REQUEST_METHOD'];

    // METHOD GET
    if ($method === 'GET') {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            if (!ctype_digit((string) $id) || (int) $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID mahasiswa tidak valid',
                    'data' => null,
                ]);
                exit;
            }

            $statement = $connection->prepare(
                'SELECT * FROM mahasiswa WHERE id = :id'
            );
            $statement->execute(['id' => (int) $id]);
            $data = $statement->fetch();

            if (!$data) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan',
                    'data' => null,
                ]);
                exit;
            }
        } else {
            $statement = $connection->query(
                'SELECT * FROM mahasiswa'
            );
            $data = $statement->fetchAll();
        }

        echo json_encode([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }

    // METHOD POST
    if ($method === 'POST') {
        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Format JSON tidak valid',
                'data' => null,
            ]);
            exit;
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $prodiId = $input['prodi_id'] ?? null;
        $angkatan = $input['angkatan'] ?? null;
        $status = $input['status'] ?? 'aktif';

        if (
            $nim === '' ||
            $nama === '' ||
            $email === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !filter_var($prodiId, FILTER_VALIDATE_INT) ||
            !filter_var($angkatan, FILTER_VALIDATE_INT) ||
            !in_array($status, ['aktif', 'cuti', 'lulus'], true)
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa tidak valid atau belum lengkap',
                'data' => null,
            ]);
            exit;
        }

        $statement = $connection->prepare(
            'INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan, status)
            VALUES
            (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );

        $statement->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => (int) $prodiId,
            'angkatan' => (int) $angkatan,
            'status' => $status,
        ]);

        $newId = (int) $connection->lastInsertId();

        $statement = $connection->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => $newId]);
        $data = $statement->fetch();

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }

    // METHOD PUT
    if ($method === 'PUT') {
        $id = $_GET['id'] ?? null;

        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID mahasiswa tidak valid',
                'data' => null,
            ]);
            exit;
        }

        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Format JSON tidak valid',
                'data' => null,
            ]);
            exit;
        }

        $nim = trim($input['nim'] ?? '');
        $nama = trim($input['nama'] ?? '');
        $email = trim($input['email'] ?? '');
        $prodiId = $input['prodi_id'] ?? null;
        $angkatan = $input['angkatan'] ?? null;
        $status = $input['status'] ?? 'aktif';

        if (
            $nim === '' ||
            $nama === '' ||
            $email === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !filter_var($prodiId, FILTER_VALIDATE_INT) ||
            !filter_var($angkatan, FILTER_VALIDATE_INT) ||
            !in_array($status, ['aktif', 'cuti', 'lulus'], true)
        ) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa tidak valid atau belum lengkap',
                'data' => null,
            ]);
            exit;
        }

        $statement = $connection->prepare(
            'SELECT id FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => (int) $id]);

        if (!$statement->fetch()) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => null,
            ]);
            exit;
        }

        $statement = $connection->prepare(
            'UPDATE mahasiswa SET
                nim = :nim,
                nama = :nama,
                email = :email,
                prodi_id = :prodi_id,
                angkatan = :angkatan,
                status = :status
            WHERE id = :id'
        );

        $statement->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => (int) $prodiId,
            'angkatan' => (int) $angkatan,
            'status' => $status,
            'id' => (int) $id,
        ]);

        $statement = $connection->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => (int) $id]);
        $data = $statement->fetch();

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diperbarui',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }

    // METHOD DELETE
    if ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;

        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID mahasiswa tidak valid',
                'data' => null,
            ]);
            exit;
        }

        // Memeriksa data mahasiswa
        $statement = $connection->prepare(
            'SELECT * FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => (int) $id]);
        $data = $statement->fetch();

        if (!$data) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan',
                'data' => null,
            ]);
            exit;
        }

        // Menghapus data mahasiswa
        $statement = $connection->prepare(
            'DELETE FROM mahasiswa WHERE id = :id'
        );
        $statement->execute(['id' => (int) $id]);

        echo json_encode([
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus',
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }

    // METHOD TIDAK DIDUKUNG
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'HTTP Method tidak diizinkan',
        'data' => null,
    ]);

} catch (\Throwable $exception) {
    \App\Core\Logger::error($exception);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server',
        'data' => null,
    ]);
}
