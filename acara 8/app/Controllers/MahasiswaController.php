<?php
namespace App\Controllers;

use App\Core\Url;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

/**
 * Controller tidak lagi membuat koneksi database sendiri:
 * MahasiswaRepository disuntikkan lewat constructor.
 */
class MahasiswaController
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    /** Simpan pesan error + input lama, lalu kembali ke halaman form. */
    private function back(string $message, string $to): void
    {
        $_SESSION['error'] = $message;
        $_SESSION['old']   = $_POST;
        Url::redirect($to);
    }

    private function pullOld(): ?array
    {
        $old = $_SESSION['old'] ?? null;
        unset($_SESSION['old']);
        return $old;
    }

    private function dbErrorMessage(PDOException $e): string
    {
        error_log($e->getMessage());
        return $e->getCode() === '23000'
            ? 'NIM sudah terdaftar atau data berkaitan dengan data lain.'
            : 'Terjadi kesalahan pada database.';
    }

    // ---------- READ ----------
    public function index(): void
    {
        $keyword = trim($_GET['keyword'] ?? '');
        $mahasiswaList = $keyword !== ''
            ? $this->repo->search($keyword)
            : $this->repo->all();

        $this->render('mahasiswa/index', compact('mahasiswaList', 'keyword'));
    }

    // ---------- CREATE ----------
    public function create(): void
    {
        $prodiList = $this->repo->prodiOptions();
        $form = $this->pullOld() ?? [
            'nim' => '', 'nama' => '', 'email' => '',
            'prodi_id' => '', 'angkatan' => date('Y'), 'status' => 'aktif',
        ];

        $this->render('mahasiswa/create', compact('prodiList', 'form'));
    }

    public function store(): void
    {
        try {
            $mhs = Mahasiswa::fromInput($_POST); // validasi lewat setter
            $this->repo->create($mhs);
        } catch (InvalidArgumentException $e) {
            $this->back($e->getMessage(), '/mahasiswa/create');
        } catch (PDOException $e) {
            $this->back($this->dbErrorMessage($e), '/mahasiswa/create');
        }

        $_SESSION['flash_message'] = 'Data mahasiswa berhasil ditambahkan!';
        Url::redirect('/mahasiswa');
    }

    // ---------- UPDATE ----------
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $mhs = $this->repo->find($id);

        if (!$mhs) {
            $_SESSION['error'] = 'Data mahasiswa tidak ditemukan.';
            Url::redirect('/mahasiswa');
        }

        $prodiList = $this->repo->prodiOptions();
        $form = $this->pullOld() ?? [
            'nim'      => $mhs->getNim(),
            'nama'     => $mhs->getNama(),
            'email'    => $mhs->getEmail(),
            'prodi_id' => $mhs->getProdiId(),
            'angkatan' => $mhs->getAngkatan(),
            'status'   => $mhs->getStatus(),
        ];

        $this->render('mahasiswa/edit', compact('id', 'prodiList', 'form'));
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        try {
            if ($this->repo->find($id) === null) {
                throw new InvalidArgumentException('Data mahasiswa tidak ditemukan.');
            }
            $mhs = Mahasiswa::fromInput($_POST);
            $mhs->setId($id);
            $this->repo->update($mhs);
        } catch (InvalidArgumentException $e) {
            $this->back($e->getMessage(), '/mahasiswa/edit?id=' . $id);
        } catch (PDOException $e) {
            $this->back($this->dbErrorMessage($e), '/mahasiswa/edit?id=' . $id);
        }

        $_SESSION['flash_message'] = 'Data mahasiswa berhasil diperbarui!';
        Url::redirect('/mahasiswa');
    }

    // ---------- DELETE ----------
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            try {
                $this->repo->delete($id);
                $_SESSION['flash_message'] = 'Data mahasiswa berhasil dihapus!';
            } catch (PDOException $e) {
                $_SESSION['error'] = $this->dbErrorMessage($e);
            }
        }

        Url::redirect('/mahasiswa');
    }
}
