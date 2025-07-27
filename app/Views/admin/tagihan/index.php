<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <h3 class="mb-4">Data Tagihan</h3>

  <!-- Flash Message -->
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
  <?php endif; ?>

  <!-- Filter Status + Download Excel -->
  <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <form method="get" action="<?= base_url('tagihan') ?>" class="d-flex align-items-center gap-2">
      <label for="status" class="form-label mb-0">Filter Status:</label>
      <select name="status" id="status" class="form-select" onchange="this.form.submit()">
        <option value="">Semua</option>
        <option value="Lunas" <?= ($filter_status == 'Lunas') ? 'selected' : '' ?>>Lunas</option>
        <option value="Belum Dibayar" <?= ($filter_status == 'Belum Dibayar') ? 'selected' : '' ?>>Belum Dibayar</option>
      </select>
    </form>

    <a href="<?= base_url('tagihan/export') ?>" class="btn btn-outline-success">
      <i class="bi bi-file-earmark-excel"></i> Download Excel
    </a>
  </div>

  <!-- Tabel Tagihan -->
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>No Pelanggan</th>
          <th>Nama</th>
          <th>Tanggal</th>
          <th>Total Pemakaian (m³)</th>
          <th>Harga/m³</th>
          <th>Total Tagihan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($tagihan)): ?>
          <?php
            $db = \Config\Database::connect();
            $currentPage = $pager->getCurrentPage('tagihan') ?? 1;
            $perPage = $pager->getPerPage('tagihan') ?? 10;
            $no = 1 + ($perPage * ($currentPage - 1));
          ?>
          <?php foreach ($tagihan as $t): ?>
            <?php
              // Hitung tarif berdasarkan tanggal pencatatan
              $tarif = $db->table('tarif_air')
                  ->where('berlaku_mulai <=', $t['tanggal_pencatatan'])
                  ->orderBy('berlaku_mulai', 'DESC')
                  ->get()
                  ->getRowArray();

              $harga_per_m3 = $tarif ? $tarif['harga_per_m3'] : 2500;

              $pemakaian = $t['meter_akhir'] - $t['meter_awal'];
              $total_tagihan = $pemakaian * $harga_per_m3;
            ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= esc($t['no_pelanggan']) ?></td>
              <td><?= esc($t['nama_lengkap']) ?></td>
              <td><?= date('d-m-Y', strtotime($t['tanggal_pencatatan'])) ?></td>
              <td><?= $pemakaian ?> m³</td>
              <td>Rp <?= number_format($harga_per_m3, 0, ',', '.') ?></td>
              <td>Rp <?= number_format($total_tagihan, 0, ',', '.') ?></td>
              <td>
                <?php if ($t['status'] === 'Lunas'): ?>
                  <span class="badge bg-success">Lunas</span>
                <?php else: ?>
                  <span class="badge bg-danger">Belum Dibayar</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">Tidak ada data tagihan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
      <div class="text-muted">
        Halaman <?= esc($currentPage) ?>
      </div>
      <div>
        <?= $pager->links('tagihan', 'bootstrap_full') ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
