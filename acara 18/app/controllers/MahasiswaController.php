<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // Mengambil semua data atau data berdasarkan ID
    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);

            if (!$data) {
                Response::json(404, false, 'Data tidak ditemukan');
            }

            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(
            200,
            true,
            'Data berhasil diambil',
            $this->model->getAll()
        );
    }

    // Menambahkan data mahasiswa
    public function store(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (
            !is_array($data) ||
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi');
        }

        // Validasi format NIM harus 9 digit angka
        if (!preg_match('/^[0-9]{9}$/', (string) $data['nim'])) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit');
        }

        // Validasi NIM duplikat
        if ($this->model->nimExists($data['nim'])) {
            Response::json(400, false, 'NIM sudah terdaftar');
        }

        $id = $this->model->create($data);

        Response::json(
            201,
            true,
            'Data berhasil ditambahkan',
            ['id' => $id]
        );
    }

    // Memperbarui data mahasiswa
    public function update($id): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (
            !is_array($data) ||
            empty($data['nim']) ||
            empty($data['nama']) ||
            empty($data['prodi'])
        ) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi');
        }

        // Validasi format NIM harus 9 digit angka
        if (!preg_match('/^[0-9]{9}$/', (string) $data['nim'])) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit');
        }

        // Validasi NIM duplikat, kecuali milik mahasiswa sendiri
        if ($this->model->nimExists($data['nim'], (int) $id)) {
            Response::json(400, false, 'NIM sudah digunakan mahasiswa lain');
        }

        $this->model->update((int) $id, $data);

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    // Menghapus data mahasiswa
    public function destroy($id): void {
        $this->model->delete((int) $id);

        Response::json(200, true, 'Data berhasil dihapus');
    }
}
