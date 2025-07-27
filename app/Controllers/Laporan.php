<?php

namespace App\Controllers;

use App\Models\TagihanModel;
use App\Models\TarifAirModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends BaseController
{
    protected function getRekapData()
    {
        $db = \Config\Database::connect();
        $tarifModel = new TarifAirModel();

        $query = $db->query("
            SELECT 
                penggunaan_air.id_user,
                penggunaan_air.tanggal_pencatatan,
                penggunaan_air.meter_awal,
                penggunaan_air.meter_akhir,
                tagihan.status
            FROM tagihan
            JOIN penggunaan_air ON tagihan.id_penggunaan = penggunaan_air.id_penggunaan
        ");

        $data_per_bulan = [];

        foreach ($query->getResultArray() as $row) {
            $bulan = date('n', strtotime($row['tanggal_pencatatan']));

            $tarif = $tarifModel
                ->where('berlaku_mulai <=', $row['tanggal_pencatatan'])
                ->orderBy('berlaku_mulai', 'DESC')
                ->first();
            $harga_per_m3 = $tarif ? $tarif['harga_per_m3'] : 2500;

            $pemakaian = $row['meter_akhir'] - $row['meter_awal'];
            $total_tagihan = $pemakaian * $harga_per_m3;

            if (!isset($data_per_bulan[$bulan])) {
                $data_per_bulan[$bulan] = [
                    'bulan' => $bulan,
                    'total_pelanggan' => 0,
                    'total_pemakaian' => 0,
                    'total_tagihan' => 0,
                    'jumlah_lunas' => 0,
                    'jumlah_belum' => 0,
                    'pelanggan_set' => []
                ];
            }

            if (!in_array($row['id_user'], $data_per_bulan[$bulan]['pelanggan_set'])) {
                $data_per_bulan[$bulan]['total_pelanggan']++;
                $data_per_bulan[$bulan]['pelanggan_set'][] = $row['id_user'];
            }

            $data_per_bulan[$bulan]['total_pemakaian'] += $pemakaian;
            $data_per_bulan[$bulan]['total_tagihan'] += $total_tagihan;
            if ($row['status'] === 'Lunas') {
                $data_per_bulan[$bulan]['jumlah_lunas']++;
            } else {
                $data_per_bulan[$bulan]['jumlah_belum']++;
            }
        }

        foreach ($data_per_bulan as &$val) {
            unset($val['pelanggan_set']);
        }

        ksort($data_per_bulan);
        return array_values($data_per_bulan);
    }

    public function index()
    {
        $data['rekap'] = $this->getRekapData();
        return view('admin/laporan/index', $data);
    }

    public function exportExcel()
    {
        $rekap = $this->getRekapData();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['Bulan', 'Jumlah Pelanggan', 'Total Pemakaian (m³)', 'Total Tagihan', 'Tagihan Lunas', 'Belum Dibayar'], NULL, 'A1');

        $row = 2;
        foreach ($rekap as $r) {
            $sheet->setCellValue("A$row", date('F', mktime(0, 0, 0, $r['bulan'], 10)));
            $sheet->setCellValue("B$row", $r['total_pelanggan']);
            $sheet->setCellValue("C$row", $r['total_pemakaian']);
            $sheet->setCellValue("D$row", $r['total_tagihan']);
            $sheet->setCellValue("E$row", $r['jumlah_lunas']);
            $sheet->setCellValue("F$row", $r['jumlah_belum']);
            $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"Rp"#,##0.00');
            $row++;foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
                }
        }

        $filename = 'laporan_bulanan.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPDF()
    {
        $rekap = $this->getRekapData();

        $html = '<h3 style="text-align:center;">Laporan Bulanan</h3>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">
                    <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th>Bulan</th>
                        <th>Jumlah Pelanggan</th>
                        <th>Total Pemakaian (m³)</th>
                        <th>Total Tagihan</th>
                        <th>Tagihan Lunas</th>
                        <th>Belum Dibayar</th>
                    </tr>
                    </thead>
                    <tbody>';

        foreach ($rekap as $r) {
            $bulan = date('F', mktime(0, 0, 0, $r['bulan'], 10));
            $html .= "<tr>
                        <td>{$bulan}</td>
                        <td>{$r['total_pelanggan']}</td>
                        <td>{$r['total_pemakaian']} m³</td>
                        <td>Rp " . number_format($r['total_tagihan'], 0, ',', '.') . "</td>
                        <td>{$r['jumlah_lunas']}</td>
                        <td>{$r['jumlah_belum']}</td>
                    </tr>";
        }

        $html .= '</tbody></table>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream('laporan_bulanan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
    }
}
