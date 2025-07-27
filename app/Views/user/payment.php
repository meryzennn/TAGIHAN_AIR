<?= $this->extend('layouts/user_layout') ?>
<?= $this->section('content') ?>

<h3>Bayar Tagihan</h3>
<p><strong>Bulan:</strong> <?= date('F Y', strtotime($tagihan['tanggal_pencatatan'])) ?></p>
<p><strong>Total:</strong> Rp <?= number_format($tagihan['total_tagihan'], 0, ',', '.') ?></p>

<button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
<div id="result-json" class="mt-3"></div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="< YOUR_CLIENT_KEY >"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function () {
    snap.pay('<?= $snapToken ?>', {
      onSuccess: function(result) {
        window.location.href = "<?= base_url('payment/finish') ?>?order_id=" + result.order_id;
      },
      onPending: function(result) {
        window.location.href = "<?= base_url('payment/finish') ?>?order_id=" + result.order_id;
      },
      onError: function(result) {
        alert('Pembayaran gagal!');
      }
    });

  };
</script>



<?= $this->endSection() ?>
