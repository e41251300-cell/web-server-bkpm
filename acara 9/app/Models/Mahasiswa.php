<?php
namespace App\Models;

use InvalidArgumentException;

/**
 * Entity Mahasiswa.
 * Semua atribut private, diakses lewat getter/setter.
 * Validasi sederhana ada di setter.
 */
class Mahasiswa
{
    public const STATUS_VALID = ['aktif', 'cuti', 'lulus'];

    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private int $angkatan = 0;
    private string $status = 'aktif';
    private ?string $prodiNama = null; // hasil JOIN, hanya untuk tampilan

    /** Bentuk objek dari baris database (data dianggap tepercaya, tanpa validasi ulang). */
    public static function fromRow(array $row): self
    {
        $m = new self();
        $m->id        = isset($row['id']) ? (int) $row['id'] : null;
        $m->nim       = (string) ($row['nim'] ?? '');
        $m->nama      = (string) ($row['nama'] ?? '');
        $m->email     = (string) ($row['email'] ?? '');
        $m->prodiId   = (int) ($row['prodi_id'] ?? 0);
        $m->angkatan  = (int) ($row['angkatan'] ?? 0);
        $m->status    = (string) ($row['status'] ?? 'aktif');
        $m->prodiNama = $row['nama_prodi'] ?? null;
        return $m;
    }

    /** Bentuk objek dari input form: setiap field divalidasi lewat setter. */
    public static function fromInput(array $in): self
    {
        $m = new self();
        $m->setNim($in['nim'] ?? '')
          ->setNama($in['nama'] ?? '')
          ->setEmail($in['email'] ?? '')
          ->setProdiId((int) ($in['prodi_id'] ?? 0))
          ->setAngkatan((int) ($in['angkatan'] ?? 0))
          ->setStatus($in['status'] ?? 'aktif');
        return $m;
    }

    // ---------- Getter ----------
    public function getId(): ?int        { return $this->id; }
    public function getNim(): string     { return $this->nim; }
    public function getNama(): string    { return $this->nama; }
    public function getEmail(): string   { return $this->email; }
    public function getProdiId(): int    { return $this->prodiId; }
    public function getAngkatan(): int   { return $this->angkatan; }
    public function getStatus(): string  { return $this->status; }
    public function getProdiNama(): ?string { return $this->prodiNama; }

    // ---------- Setter (dengan validasi) ----------
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setNim(string $nim): self
    {
        $nim = trim($nim);
        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka dan tidak boleh kosong.');
        }
        $this->nim = $nim;
        return $this;
    }

    public function setNama(string $nama): self
    {
        $nama = trim($nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }
        $this->nama = $nama;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $email = trim($email);
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        $this->email = $email;
        return $this;
    }

    public function setProdiId(int $prodiId): self
    {
        if ($prodiId <= 0) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }
        $this->prodiId = $prodiId;
        return $this;
    }

    public function setAngkatan(int $angkatan): self
    {
        if ($angkatan < 1900 || $angkatan > (int) date('Y') + 1) {
            throw new InvalidArgumentException('Angkatan tidak valid.');
        }
        $this->angkatan = $angkatan;
        return $this;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::STATUS_VALID, true)) {
            throw new InvalidArgumentException('Status harus salah satu dari: ' . implode(', ', self::STATUS_VALID) . '.');
        }
        $this->status = $status;
        return $this;
    }
}
