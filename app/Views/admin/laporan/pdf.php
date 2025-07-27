<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tagihan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 5px; }
    </style>
</head>
<body>
    <h3>Laporan Tagihan Air</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Pelanggan</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Pemakaian (m³)</th>
                <th>Total Tagihan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tagihan as $i => $t): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $t['no_pelanggan'] ?></td>
                <td><?= $t['nama_lengkap'] ?></td>
                <td><?= date('d-m-Y', strtotime($t['tanggal_pencatatan'])) ?></td>
                <td><?= $t['meter_akhir'] - $t['meter_awal'] ?> m³</td>
                <td>Rp <?= number_format($t['total_tagihan'], 0, ',', '.') ?></td>
                <td><?= $t['status'] ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
