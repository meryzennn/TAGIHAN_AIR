<?= $this->extend('layouts/user_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Selamat Datang, <?= session('nama_lengkap') ?>!</h3>
  <p class="text-muted">Pantau penggunaan air dan tagihan Anda di sini.</p>

  <div class="row">
    <div class="col-md-4">
      <div class="card border-primary mb-4">
        <div class="card-body">
          <h5 class="card-title">Total Pemakaian Bulan Ini</h5>
          <p class="card-text fs-4"><?= $pemakaian ?? '0' ?> m³</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card border-warning mb-4">
        <div class="card-body">
          <h5 class="card-title">Tagihan Belum Dibayar</h5>
          <p class="card-text fs-4 text-danger">Rp <?= number_format($tagihan_belum ?? 0, 0, ',', '.') ?></p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card border-success mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-1">No Pelanggan</h5>
            <p class="card-text fs-5 mb-0" id="noPelanggan"><?= session('no_pelanggan') ?: '-' ?></p>
          </div>
          <button class="btn btn-outline-secondary btn-sm" onclick="copyNo()">
            <i class="bi bi-clipboard"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="card mt-4">
    <div class="card-header bg-light">
      <strong>Riwayat Penggunaan Air</strong>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Bulan</th>
            <th>Meter Awal</th>
            <th>Meter Akhir</th>
            <th>Pemakaian (m³)</th>
            <th>Total Tagihan</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($riwayat)): ?>
            <?php foreach ($riwayat as $r): ?>
              <tr>
                <td><?= date('F Y', strtotime($r['tanggal_pencatatan'])) ?></td>
                <td><?= $r['meter_awal'] ?></td>
                <td><?= $r['meter_akhir'] ?></td>
                <td><?= $r['pemakaian'] ?> m³</td>
                <td>Rp <?= number_format($r['total_tagihan'], 0, ',', '.') ?></td>
                <td>
                  <?= esc($r['status']) ?>
                  <?php if ($r['status'] == 'Belum Dibayar'): ?>
                    <br>
                    <a href="<?= base_url('payment/index/' . $r['id_tagihan']) ?>" class="btn btn-sm btn-warning mt-1">
                      Bayar
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">Belum ada data penggunaan.</td></tr>
          <?php endif; ?>
        </tbody>


      </table>


      <?php if ($pager): ?>
      <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <div class="text-muted">
          <?= $pager->getCurrentPage('riwayat') ? 'Halaman ' . $pager->getCurrentPage('riwayat') : '' ?>
        </div>
        <div>
          <?= $pager->links('riwayat', 'bootstrap_full') ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Script Copy Clipboard -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copyNo() {
  const no = document.getElementById('noPelanggan').innerText;
  navigator.clipboard.writeText(no).then(() => {
    Swal.fire({
      icon: 'success',
      title: 'Disalin!',
      text: `No Pelanggan ${no} berhasil disalin.`,
      timer: 1500,
      showConfirmButton: false
    });
  }).catch(err => {
    console.error('Gagal menyalin:', err);
  });
}
</script>

<?= $this->endSection() ?>
