<?php

namespace App\Controllers;

use App\Models\TagihanModel;
use App\Models\PenggunaanAirModel;
use App\Models\TarifAirModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Tagihan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $penggunaanModel = new PenggunaanAirModel();
        $tarifModel = new TarifAirModel();
        $tagihanModel = new TagihanModel();

        $builder = $tagihanModel
            ->select('
                tagihan.id_tagihan,
                tagihan.status,
                tagihan.created_at,
                penggunaan_air.id_penggunaan,
                penggunaan_air.tanggal_pencatatan,
                penggunaan_air.meter_awal,
                penggunaan_air.meter_akhir,
                users.no_pelanggan,
                users.nama_lengkap
            ')
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user');

        if ($status && in_array($status, ['Lunas', 'Belum Dibayar'])) {
            $builder->where('tagihan.status', $status);
        }

        // Pagination
        $perPage = 10;
        $result = $builder->paginate($perPage, 'tagihan');

        foreach ($result as &$row) {
            $tarif = $tarifModel
                ->where('berlaku_mulai <=', $row['tanggal_pencatatan'])
                ->orderBy('berlaku_mulai', 'DESC')
                ->first();

            $harga = $tarif ? $tarif['harga_per_m3'] : 2500;
            $pemakaian = $row['meter_akhir'] - $row['meter_awal'];
            $row['pemakaian'] = $pemakaian;
            $row['total_tagihan'] = $pemakaian * $harga;
        }

        $data = [
            'tagihan' => $result,
            'filter_status' => $status ?? '',
            'pager' => $tagihanModel->pager,
            'currentPage' => $tagihanModel->pager->getCurrentPage('tagihan'),
            'perPage' => $perPage
        ];

        return view('admin/tagihan/index', $data);
    }

    public function generate()
    {
        $penggunaanModel = new PenggunaanAirModel();
        $tagihanModel    = new TagihanModel();
        $tarifModel      = new TarifAirModel();

        $penggunaan = $penggunaanModel->findAll();

        foreach ($penggunaan as $p) {
            $existing = $tagihanModel->where('id_penggunaan', $p['id_penggunaan'])->first();
            if ($existing) continue;

            $tarif = $tarifModel
                ->where('berlaku_mulai <=', $p['tanggal_pencatatan'])
                ->orderBy('berlaku_mulai', 'DESC')
                ->first();

            $harga_per_m3 = $tarif ? $tarif['harga_per_m3'] : 2500;

            $total_pemakaian = $p['meter_akhir'] - $p['meter_awal'];
            $total_tagihan   = $total_pemakaian * $harga_per_m3;

            $tagihanModel->insert([
                'id_penggunaan' => $p['id_penggunaan'],
                'total_tagihan' => $total_tagihan,
                'status'        => 'Belum Dibayar',
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/tagihan')->with('success', 'Tagihan berhasil digenerate.');
    }

    public function export()
{
    $tarifModel = new TarifAirModel();

    $builder = $this->db->table('tagihan');
    $builder->select('
        tagihan.*, 
        penggunaan_air.tanggal_pencatatan, 
        penggunaan_air.meter_awal, 
        penggunaan_air.meter_akhir,
        users.no_pelanggan, 
        users.nama_lengkap
    ');
    $builder->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan');
    $builder->join('users', 'users.id_user = penggunaan_air.id_user');
    $data = $builder->get()->getResultArray();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom (tambahkan Harga/m³)
    $sheet->fromArray([
        ['No', 'No Pelanggan', 'Nama', 'Tanggal', 'Pemakaian (m³)', 'Harga/m³', 'Total Tagihan', 'Status']
    ], NULL, 'A1');

    $row = 2;
    foreach ($data as $i => $t) {
        $tarif = $tarifModel
            ->where('berlaku_mulai <=', $t['tanggal_pencatatan'])
            ->orderBy('berlaku_mulai', 'DESC')
            ->first();

        $harga = $tarif ? $tarif['harga_per_m3'] : 2500;
        $pemakaian = $t['meter_akhir'] - $t['meter_awal'];
        $total_tagihan = $pemakaian * $harga;

        // Isi data
        $sheet->setCellValue("A{$row}", $i + 1);
        $sheet->setCellValue("B{$row}", $t['no_pelanggan']);
        $sheet->setCellValue("C{$row}", $t['nama_lengkap']);
        $sheet->setCellValue("D{$row}", $t['tanggal_pencatatan']);
        $sheet->setCellValue("E{$row}", $pemakaian);
        $sheet->setCellValue("F{$row}", $harga);
        $sheet->setCellValue("G{$row}", $total_tagihan);
        $sheet->setCellValue("H{$row}", $t['status']);

        // Format Rp untuk kolom Harga/m³ dan Total Tagihan
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('"Rp"#,##0.00');
        $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('"Rp"#,##0.00');

        $row++;
    }

    // Atur agar semua kolom auto-size
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = 'data_tagihan_' . date('Ymd_His') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}
