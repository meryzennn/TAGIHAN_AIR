<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<h2 class="fw-bold mb-3">Selamat Datang, Admin <?= session('username') ?></h2>
<p class="text-muted">Pantau dan kelola sistem tagihan air Anda dari sini.</p>

<!-- Stat Cards -->
<div class="row mt-4">
  <div class="col-md-4 mb-3">
    <div class="card stat-card text-bg-primary text-white">
      <div class="card-body">
        <h6>Total Pelanggan</h6>
        <h3 class="fw-bold"><?= $totalPelanggan ?></h3>
        <p class="mb-0"><i class="bi bi-people-fill"></i> Aktif & terdaftar</p>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card text-bg-warning text-dark">
      <div class="card-body">
        <h6>Tagihan Belum Dibayar</h6>
        <h3 class="fw-bold"><?= $tagihanBelum ?></h3>
        <p class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Menunggu pembayaran</p>
      </div>
    </div>
  </div>
  <div class="col-md-4 mb-3">
    <div class="card stat-card text-bg-success text-white">
      <div class="card-body">
        <h6>Tagihan Lunas</h6>
        <h3 class="fw-bold"><?= $tagihanLunas ?></h3>
        <p class="mb-0"><i class="bi bi-check-circle-fill"></i> Pembayaran berhasil</p>
      </div>
    </div>
  </div>
</div>

<!-- Grafik Placeholder -->
<div class="card mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Grafik Penggunaan Air</h5>
    <canvas id="waterChart" height="100"></canvas>
  </div>
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('waterChart');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= $bulan ?>,
      datasets: [{
        label: 'Pemakaian Air (m³)',
        data: <?= $totalPemakaian ?>,
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13,110,253,0.1)',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true } }
    }
  });
</script>

<?= $this->endSection() ?>
