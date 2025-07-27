<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container">
  <h3 class="mb-4">Data Pelanggan</h3>

  <!-- Tombol Export -->
  <a href="<?= base_url('pelanggan/export') ?>" class="btn btn-success mb-3">
    <i class="bi bi-file-earmark-excel"></i> Export Excel
  </a>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Nama Lengkap</th>
          <th>No Pelanggan</th>
          <th>Alamat</th>
          <th>No HP</th>
          <th>Tanggal Daftar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($pelanggan)): ?>
          <?php foreach ($pelanggan as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($p['username']) ?></td>
              <td><?= esc($p['nama_lengkap']) ?></td>
              <td><?= esc($p['no_pelanggan']) ?></td>
              <td><?= esc($p['alamat']) ?></td>
              <td><?= esc($p['no_hp']) ?></td>
              <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
              <td>
                <a href="<?= base_url("pelanggan/edit/" . $p['id_user']) ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <button type="button" class="btn btn-sm btn-danger btn-delete"
                  data-id="<?= $p['id_user'] ?>"
                  data-nama="<?= esc($p['nama_lengkap']) ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">Tidak ada data pelanggan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- SweetAlert Success -->
<?php if (session()->getFlashdata('success')): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Sukses!',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2000,
    showConfirmButton: false
  });
</script>
<?php endif; ?>

<!-- SweetAlert Error -->
<?php if (session()->getFlashdata('error')): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= session()->getFlashdata('error') ?>',
    confirmButtonText: 'OK'
  });
</script>
<?php endif; ?>

<!-- SweetAlert Delete Confirmation -->
<script>
  document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
      const id = this.getAttribute('data-id');
      const nama = this.getAttribute('data-nama');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Data pelanggan "${nama}" akan dihapus.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = `/pelanggan/delete/${id}`;
        }
      });
    });
  });
</script>

<?= $this->endSection() ?>
