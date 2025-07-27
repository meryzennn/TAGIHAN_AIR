<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Dashboard Pelanggan' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .sidebar .top-links {
      flex-grow: 1;
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
  $uri = service('uri')->getSegment(2); // untuk user/dashboard atau user/bayar
?>

<!-- Sidebar -->
<div class="sidebar">
  <div class="top-links">
    <h2 class="mb-5">Air Bersih</h2>
    
    <a href="<?= base_url('/user/dashboard') ?>" class="<?= $uri === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-house-door"></i> Dashboard
    </a>
  </div>

  <!-- Tombol Logout di Bawah -->
  <a href="#" onclick="confirmLogout(event)">
    <i class="bi bi-box-arrow-right"></i> Logout
  </a>
</div>

<!-- Main Content -->
<div class="main-content">
  <?= $this->renderSection('content') ?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmLogout(e) {
  e.preventDefault();

  Swal.fire({
    title: 'Yakin ingin logout?',
    text: "Sesi Anda akan diakhiri.",
    icon: 'warning',
    iconColor: '#ffc107',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, logout',
    cancelButtonText: 'Batal',
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "<?= base_url('logout') ?>";
    }
  });
}
</script>

</body>
</html>
