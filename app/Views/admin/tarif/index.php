<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Tarif Air</h3>

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>

  <form action="<?= base_url('/tarif/update') ?>" method="post">
    <div class="mb-3">
      <label for="harga_per_m3" class="form-label">Harga per m³ (Rp)</label>
      <input type="number" class="form-control" name="harga_per_m3" id="harga_per_m3"
             value="<?= $tarif['harga_per_m3'] ?? '' ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan Tarif</button>
  </form>
</div>

<?= $this->endSection() ?>
