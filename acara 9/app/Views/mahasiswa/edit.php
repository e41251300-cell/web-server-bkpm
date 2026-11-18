<?php
/**
 * @var int   $id
 * @var array $form
 * @var array $prodiList
 */
use App\Core\Url;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Data Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?= Url::to('/mahasiswa/update') ?>" method="POST">
                            <input type="hidden" name="id" value="<?= (int) $id ?>">
                            <?php require __DIR__ . '/_form_fields.php'; ?>
                            <div class="d-flex justify-content-between">
                                <a href="<?= Url::to('/mahasiswa') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
