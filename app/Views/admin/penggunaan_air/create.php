<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
  <h3 class="mb-4">Catat Penggunaan Air</h3>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= base_url('/penggunaan-air/store') ?>" method="post">
    
    <!-- Nomor Pelanggan (Autocomplete) -->
  <div class="mb-3">
    <label for="no_pelanggan" class="form-label">Nomor Pelanggan</label>
    <input type="text" name="no_pelanggan" id="no_pelanggan" class="form-control" list="listPelanggan" required>

    <datalist id="listPelanggan">
      <?php foreach ($pelanggan as $p): ?>
        <option value="<?= esc($p['no_pelanggan']) ?>">
          <?= esc($p['no_pelanggan']) ?> - <?= esc($p['nama_lengkap']) ?>
        </option>
      <?php endforeach; ?>
    </datalist>
  </div>


    <!-- Tanggal Pencatatan -->
    <div class="mb-3">
      <label for="tanggal_pencatatan" class="form-label">Tanggal Pencatatan</label>
      <input type="date" name="tanggal_pencatatan" id="tanggal_pencatatan" class="form-control" required>
    </div>

    <!-- Meter Awal -->
    <div class="mb-3">
      <label for="meter_awal" class="form-label">Meter Awal (m³)</label>
      <input type="number" name="meter_awal" id="meter_awal" class="form-control" min="0" required>
    </div>

    <!-- Meter Akhir -->
    <div class="mb-3">
      <label for="meter_akhir" class="form-label">Meter Akhir (m³)</label>
      <input type="number" name="meter_akhir" id="meter_akhir" class="form-control" min="0" required>
    </div>

    <!-- Tombol -->
    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
      <a href="<?= base_url('/penggunaan-air') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Batal</a>
    </div>

  </form>
</div>

<?= $this->endSection() ?>
