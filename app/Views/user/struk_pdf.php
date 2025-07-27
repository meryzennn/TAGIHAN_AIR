<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 8px; }
        .total { font-size: 16px; font-weight: bold; }
        .center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h2>Struk Pembayaran Air</h2>
    <p>Air Bersih - Bukti Transaksi</p>
    <hr>
</div>

<table>
    <tr>
        <td><strong>Nama</strong></td>
        <td><?= $tagihan['nama_lengkap'] ?></td>
    </tr>
    <tr>
        <td><strong>No Pelanggan</strong></td>
        <td><?= $tagihan['no_pelanggan'] ?></td>
    </tr>
    <tr>
        <td><strong>Bulan</strong></td>
        <td><?= date('F Y', strtotime($tagihan['tanggal_pencatatan'])) ?></td>
    </tr>
    <tr>
        <td><strong>Meter Awal</strong></td>
        <td><?= $tagihan['meter_awal'] ?> m³</td>
    </tr>
    <tr>
        <td><strong>Meter Akhir</strong></td>
        <td><?= $tagihan['meter_akhir'] ?> m³</td>
    </tr>
    <tr>
        <td><strong>Pemakaian</strong></td>
        <td><?= $tagihan['meter_akhir'] - $tagihan['meter_awal'] ?> m³</td>
    </tr>
    <tr>
        <td><strong>Total Tagihan</strong></td>
        <td>Rp <?= number_format($tagihan['total_tagihan'], 0, ',', '.') ?></td>
    </tr>
    <tr>
        <td><strong>Status</strong></td>
        <td><?= $tagihan['status'] ?></td>
    </tr>
</table>

<br><br>
<p class="center">Terima kasih telah melakukan pembayaran.</p>
<p class="center">Tanggal Cetak: <?= date('d-m-Y H:i:s') ?></p>

</body>
</html>
