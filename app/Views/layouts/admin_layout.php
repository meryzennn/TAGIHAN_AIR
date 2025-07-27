<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Admin Panel' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Flatpickr (untuk tanggal) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    body {
      min-height: 100vh;
      display: flex;
      background-color: #f5f6fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      width: 270px;
      background: #e0e0e0;
      padding: 25px 15px;
      box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    .sidebar a {
      display: block;
      padding: 10px 15px;
      text-decoration: none;
      color: #333;
      border-radius: 8px;
      margin-bottom: 8px;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #ced4da;
      font-weight: bold;
    }
    .main-content {
      flex-grow: 1;
      padding: 40px;
    }
  </style>
</head>
<body>

<?php
  // Tangkap URI yang sedang aktif
  $currentUri = service('uri')->getSegment(1);
?>

<!-- Sidebar -->
<div class="sidebar">
  <h2 class="mb-5">Admin Panel</h2>

  <a href="<?= base_url('/dashboard/admin') ?>" class="<?= $currentUri === 'dashboard' ? 'active' : '' ?>">
    <i class="bi bi-house-door"></i> Dashboard
  </a>

  <a href="<?= base_url('/pelanggan') ?>" class="<?= $currentUri === 'pelanggan' ? 'active' : '' ?>">
    <i class="bi bi-people-fill"></i> Data Pelanggan
  </a>

  <a href="<?= base_url('/penggunaan-air') ?>" class="<?= $currentUri === 'penggunaan-air' ? 'active' : '' ?>">
    <i class="bi bi-droplet"></i> Penggunaan Air
  </a>

  <a href="<?= base_url('/tagihan') ?>" class="<?= $currentUri === 'tagihan' ? 'active' : '' ?>">
    <i class="bi bi-cash-stack"></i> Tagihan
  </a>

  <a href="<?= base_url('admin/laporan') ?>" class="<?= (uri_string() == 'admin/laporan') ? 'active' : '' ?>">
    <i class="bi bi-file-earmark-text"></i> Laporan
  </a>

  <a class="<?= $currentUri === 'tarif' ? 'active' : '' ?>" href="<?= base_url('/tarif') ?>">
    <i class="bi bi-currency-dollar"></i> Tarif Air
  </a>

  <!-- Logout dengan konfirmasi -->
  <a href="#" onclick="confirmLogout()">
    <i class="bi bi-box-arrow-right"></i> Logout
  </a>
</div>

<!-- Main Content -->
<div class="main-content">
  <?= $this->renderSection('content') ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Konfirmasi Logout -->
<script>
  function confirmLogout() {
    Swal.fire({
      title: 'Yakin ingin logout?',
      icon: 'warning',
      iconColor: '#ffc107',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, logout',
      cancelButtonText: 'Batal',

      color: '#333'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "<?= base_url('/logout') ?>";
      }
    });
  }
</script>

<!-- SweetAlert Jika Flashdata Success -->
<?php if (session()->getFlashdata('success')): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= session()->getFlashdata('success') ?>',
    timer: 2500,
    showConfirmButton: false
  });
</script>
<?php endif; ?>

</body>
</html>
