<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="bg-light d-flex align-items-center" style="height:100vh">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="text-center mb-3">Login</h4>

            <!-- Flashdata error (fallback untuk user non-JS) -->
            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/auth/login') ?>" method="post">
              <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
              </div>

              <div class="mb-3">
                <label>Password</label>
                <div class="input-group">
                  <input type="password" name="password" id="password" class="form-control" required>
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="bi bi-eye" id="iconEye"></i>
                  </button>
                </div>
              </div>

              <button class="btn btn-primary w-100">Login</button>
              <a href="/register" class="btn btn-link w-100 text-center mt-2">Belum punya akun? Daftar</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Toggle Show/Hide Password -->
  <script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const icon = document.getElementById('iconEye');

    toggle.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      icon.classList.toggle('bi-eye');
      icon.classList.toggle('bi-eye-slash');
    });
  </script>

</body>
</html>
