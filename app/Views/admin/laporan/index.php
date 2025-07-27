<?= $this->extend('layouts/admin_layout') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Laporan Bulanan</h3>

<a href="<?= base_url('admin/laporan/export/excel') ?>" class="btn btn-success mb-3">Export Excel</a>
<a href="<?= base_url('admin/laporan/pdf') ?>" class="btn btn-danger mb-3">Export PDF</a>


<div class="table-responsive">
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Bulan</th>
        <th>Jumlah Pelanggan</th>
        <th>Total Pemakaian (m³)</th>
        <th>Total Tagihan</th>
        <th>Tagihan Lunas</th>
        <th>Belum Dibayar</th>
      </tr>
    </thead>

    <tbody>
    <?php if (!empty($rekap)): ?>
        <?php foreach ($rekap as $r): ?>
        <tr>
            <td><?= date('F', mktime(0, 0, 0, $r['bulan'], 10)) ?></td>
            <td><?= $r['total_pelanggan'] ?></td>
            <td><?= $r['total_pemakaian'] ?> m³</td>
            <td>Rp <?= number_format($r['total_tagihan'], 0, ',', '.') ?></td>
            <td><?= $r['jumlah_lunas'] ?></td>
            <td><?= $r['jumlah_belum'] ?></td>
        </tr>
        <?php endforeach ?>
    <?php else: ?>
        <tr>
        <td colspan="7" class="text-center">Tidak ada laporan.</td>
        </tr>
    <?php endif; ?>
    </tbody>

  </table>
</div>

<?= $this->endSection() ?>
