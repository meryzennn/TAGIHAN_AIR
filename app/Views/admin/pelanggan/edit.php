<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container">
  <h3 class="mb-4">Edit Data Pelanggan</h3>

  <form action="<?= base_url('/pelanggan/update/' . $pelanggan['id_user']) ?>" method="post">
    <div class="mb-3">
      <label for="no_pelanggan" class="form-label">Nomor Pelanggan</label>
      <input type="text" name="no_pelanggan" id="no_pelanggan" class="form-control" value="<?= esc($pelanggan['no_pelanggan']) ?>" readonly>
    </div>
    <div class="mb-3">
      <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?= esc($pelanggan['nama_lengkap']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="alamat" class="form-label">Alamat</label>
      <textarea name="alamat" id="alamat" class="form-control" required><?= esc($pelanggan['alamat']) ?></textarea>
    </div>
    <div class="mb-3">
      <label for="no_hp" class="form-label">No. HP</label>
      <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?= esc($pelanggan['no_hp']) ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('/pelanggan') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>
<?= $this->endSection() ?>
