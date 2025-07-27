<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Edit Penggunaan Air</h3>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= base_url('/penggunaan-air/update/' . $penggunaan['id_penggunaan']) ?>" method="post">
    <div class="mb-3">
      <label for="id_user" class="form-label">Nomor Pelanggan</label>
      <select name="id_user" id="id_user" class="form-select" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php foreach ($pelanggan as $p): ?>
          <option value="<?= $p['id_user'] ?>" <?= $p['id_user'] == $penggunaan['id_user'] ? 'selected' : '' ?>>
            <?= $p['no_pelanggan'] ?> - <?= $p['nama_lengkap'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="tanggal_pencatatan" class="form-label">Tanggal Pencatatan</label>
      <input type="date" name="tanggal_pencatatan" id="tanggal_pencatatan" class="form-control"
             value="<?= $penggunaan['tanggal_pencatatan'] ?>" required>
    </div>

    <div class="mb-3">
      <label for="meter_awal" class="form-label">Meter Awal (m³)</label>
      <input type="number" name="meter_awal" id="meter_awal" class="form-control"
             value="<?= $penggunaan['meter_awal'] ?>" required>
    </div>

    <div class="mb-3">
      <label for="meter_akhir" class="form-label">Meter Akhir (m³)</label>
      <input type="number" name="meter_akhir" id="meter_akhir" class="form-control"
             value="<?= $penggunaan['meter_akhir'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Perbarui</button>
    <a href="<?= base_url('/penggunaan-air') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>

<?= $this->endSection() ?>
