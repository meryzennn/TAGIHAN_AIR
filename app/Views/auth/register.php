<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Registrasi Akun Pelanggan</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height:100vh">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="text-center mb-3">Daftar Akun Pelanggan</h4>

            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/register/save') ?>" method="post">

              <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
              </div>

              <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" required>
              </div>

              <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
              </div>

              <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control" required pattern="\d{10,13}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <small class="form-text text-muted">Masukkan nomor HP tanpa spasi atau karakter khusus.</small>
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

              <div class="mb-3">
                <label>Konfirmasi Password</label>
                <div class="input-group">
                  <input type="password" name="password_confirm" id="passwordConfirm" class="form-control" required>
                  <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                    <i class="bi bi-eye" id="iconConfirmEye"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn btn-success w-100">Daftar</button>
              <a href="/login" class="btn btn-link w-100 text-center mt-2">Sudah punya akun? Login di sini</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script show/hide password -->
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

    const toggleConfirm = document.getElementById('toggleConfirmPassword');
    const passwordConfirm = document.getElementById('passwordConfirm');
    const iconConfirm = document.getElementById('iconConfirmEye');

    toggleConfirm.addEventListener('click', () => {
      const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordConfirm.setAttribute('type', type);
      iconConfirm.classList.toggle('bi-eye');
      iconConfirm.classList.toggle('bi-eye-slash');
    });
  </script>

</body>
</html>
