<?php
/**
 * Field form bersama untuk create & edit.
 * @var array $form
 * @var array $prodiList
 */
?>
<div class="mb-3">
    <label class="form-label">NIM</label>
    <input type="text" name="nim" class="form-control" inputmode="numeric" pattern="[0-9]+"
           title="NIM harus berupa angka" value="<?= htmlspecialchars((string) $form['nim']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars((string) $form['nama']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars((string) $form['email']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Program Studi</label>
    <select name="prodi_id" class="form-select" required>
        <option value="">-- Pilih Prodi --</option>
        <?php foreach ($prodiList as $p): ?>
            <option value="<?= $p['id'] ?>" <?= (int) $p['id'] === (int) $form['prodi_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nama']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Angkatan</label>
    <input type="number" name="angkatan" class="form-control" value="<?= htmlspecialchars((string) $form['angkatan']) ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <?php foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'lulus' => 'Lulus'] as $val => $label): ?>
            <option value="<?= $val ?>" <?= $form['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>
</div>
