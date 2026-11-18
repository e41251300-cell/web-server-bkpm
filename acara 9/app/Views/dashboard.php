<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SI Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <h2>Dashboard SI Akademik</h2>
                <p class="text-muted">Selamat datang, <b><?= htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></b>!</p>
                <hr>
                <div class="d-flex gap-2">
                    <!-- Link memakai Url::to() sehingga otomatis mengikuti folder -->
                    <a href="<?= \App\Core\Url::to('/mahasiswa') ?>" class="btn btn-primary">Kelola Data Mahasiswa</a>
                    <a href="<?= \App\Core\Url::to('/logout') ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>