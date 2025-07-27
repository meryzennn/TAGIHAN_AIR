<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Tambah Pelanggan</h3>

  <form action="<?= base_url('/pelanggan/store') ?>" method="post">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" id="username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
      <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="alamat" class="form-label">Alamat</label>
      <textarea name="alamat" id="alamat" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
      <label for="no_hp" class="form-label">No. HP</label>
      <input type="text" name="no_hp" id="no_hp" class="form-control" required>
    </div>

    <!-- Nomor Pelanggan di-generate otomatis, tidak perlu ditampilkan -->
    <!-- Bisa juga pakai input hidden kalau mau simpan di form -->
    <!-- <input type="hidden" name="no_pelanggan" value="<?= 'PLG' . rand(1000, 9999) ?>"> -->

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('/pelanggan') ?>" class="btn btn-secondary">Batal</a>
  </form>
</div>

<?= $this->endSection() ?>
