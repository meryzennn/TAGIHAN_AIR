<?= $this->extend('layouts/user_layout') ?>
<?= $this->section('content') ?>

<div class="container">
  <div class="text-center mt-5">
    <h3>Pembayaran Berhasil 🎉</h3>
    <p>Terima kasih, <?= esc($tagihan['nama_lengkap']) ?>. Pembayaran Anda sebesar:</p>
    <h4 class="text-success">Rp <?= number_format($tagihan['total_tagihan'], 0, ',', '.') ?></h4>
    <p>Status: <strong><?= esc($tagihan['status']) ?></strong></p>

    <a href="<?= base_url('/struk/' . $tagihan['id_tagihan']) ?>" class="btn btn-outline-primary mt-3" target="_blank">
      <i class="bi bi-file-earmark-pdf"></i> Unduh Bukti Pembayaran (PDF)
    </a>

    <br><br>
    <a href="<?= base_url('/user/dashboard') ?>" class="btn btn-secondary mt-2">
      Kembali ke Dashboard
    </a>
  </div>
</div>

<?= $this->endSection() ?>
