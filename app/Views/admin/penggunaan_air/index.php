<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Data Penggunaan Air</h3>

  <a href="/penggunaan-air/create" class="btn btn-primary mb-3">
    <i class="bi bi-plus-circle"></i> Tambah Penggunaan
  </a>
  <a href="<?= base_url('/penggunaan-air/export') ?>" class="btn btn-success mb-3">
  <i class="bi bi-file-earmark-excel"></i> Export Excel
  </a>


  <div class="table-responsive">
    <table class="table table-bordered table-striped w-100">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>No Pelanggan</th>
          <th>Nama Pelanggan</th>
          <th>Tanggal</th>
          <th>Meter Awal</th>
          <th>Meter Akhir</th>
          <th>Total Pemakaian (m³)</th>
          <th>Tagihan (Rp)</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($penggunaan)): ?>
          <?php foreach ($penggunaan as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($p['no_pelanggan']) ?></td>
              <td><?= esc($p['nama_lengkap']) ?></td>
              <td><?= date('d F Y', strtotime($p['tanggal_pencatatan'])) ?></td>
              <td><?= esc($p['meter_awal']) ?></td>
              <td><?= esc($p['meter_akhir']) ?></td>
              <td><?= $p['total_pemakaian'] ?> m³</td>
              <td>Rp <?= number_format($p['tagihan'], 0, ',', '.') ?></td>
              <td>
                <a href="<?= base_url('/penggunaan-air/edit/' . $p['id_penggunaan']) ?>" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="<?= $p['id_penggunaan'] ?>">
                  <i class="bi bi-trash"></i> Hapus
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">Belum ada data penggunaan air.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <script>
      // SweetAlert konfirmasi hapus
      document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();
          const id = this.getAttribute('data-id');

          Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data penggunaan air akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              window.location.href = `/penggunaan-air/delete/${id}`;
            }
          });
        });
      });
    </script>

<?= $this->endSection() ?>
