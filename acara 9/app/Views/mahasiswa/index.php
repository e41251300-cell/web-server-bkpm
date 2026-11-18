<?php
/**
 * @var App\Models\Mahasiswa[] $mahasiswaList
 * @var string $keyword
 */
use App\Core\Url;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa - SI Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">

        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Mahasiswa</h5>
                <div>
                    <a href="<?= Url::to('/mahasiswa/create') ?>" class="btn btn-sm btn-success">+ Tambah Mahasiswa</a>
                    <a href="<?= Url::to('/dashboard') ?>" class="btn btn-sm btn-light">Dashboard</a>
                </div>
            </div>
            <div class="card-body">

                <!-- Pencarian berdasarkan NIM / Nama -->
                <form action="<?= Url::to('/mahasiswa') ?>" method="GET" class="row g-2 mb-3">
                    <div class="col-auto">
                        <input type="text" name="keyword" class="form-control" placeholder="Cari NIM atau Nama..."
                               value="<?= htmlspecialchars($keyword) ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary">Cari</button>
                        <?php if ($keyword !== ''): ?>
                            <a href="<?= Url::to('/mahasiswa') ?>" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Prodi</th>
                                <th>Angkatan</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($mahasiswaList)): ?>
                                <?php $no = 1; foreach ($mahasiswaList as $mhs): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                                        <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                                        <td><?= htmlspecialchars($mhs->getEmail()) ?></td>
                                        <td><?= htmlspecialchars($mhs->getProdiNama() ?? '-') ?></td>
                                        <td><?= $mhs->getAngkatan() ?></td>
                                        <td>
                                            <span class="badge bg-<?= $mhs->getStatus() === 'aktif' ? 'success' : ($mhs->getStatus() === 'cuti' ? 'warning' : 'secondary') ?>">
                                                <?= ucfirst(htmlspecialchars($mhs->getStatus())) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= Url::to('/mahasiswa/edit?id=' . $mhs->getId()) ?>" class="btn btn-sm btn-warning">Edit</a>

                                                <!-- Hapus: method POST + konfirmasi JavaScript -->
                                                <form action="<?= Url::to('/mahasiswa/delete') ?>" method="POST"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    <input type="hidden" name="id" value="<?= $mhs->getId() ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Data mahasiswa tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
