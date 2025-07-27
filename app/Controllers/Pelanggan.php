<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Pelanggan extends Controller
{
    public function index()
    {
        $model = new \App\Models\PelangganModel();
        $data['pelanggan'] = $model->where('role', 'pelanggan')->findAll();

        return view('admin/pelanggan/index', $data);
    }


    public function create()
    {
        $data['title'] = 'Tambah Pelanggan';
        return view('admin/pelanggan/create', $data);
    }

    public function store()
    {
    $model = new PelangganModel();

    // Generate no_pelanggan otomatis, contoh: PLG20250723001
    $no_pelanggan = 'PLG' . date('Ymd') . rand(100, 999);

    $password = $this->request->getPost('password');
    $username = $this->request->getPost('username');

    // Validasi sederhana
    if ($model->where('username', $username)->first()) {
        return redirect()->back()->with('error', 'Username sudah digunakan!');
    }

    $data = [
        'username'      => $username,
        'password'      => password_hash($password, PASSWORD_DEFAULT),
        'role'          => 'pelanggan',
        'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
        'alamat'        => $this->request->getPost('alamat'),
        'no_hp'         => $this->request->getPost('no_hp'),
        'no_pelanggan'  => $no_pelanggan,
        'created_at'    => date('Y-m-d H:i:s')
    ];

    $model->insert($data);

    return redirect()->to('/pelanggan')->with('success', 'Pelanggan berhasil ditambahkan!');
}

    public function delete($id)
    {
        $model = new PelangganModel();
        $penggunaanModel = new \App\Models\PenggunaanAirModel();

        // Cek apakah masih ada data penggunaan air terkait pelanggan ini
        $penggunaan = $penggunaanModel->where('id_user', $id)->first();

        if ($penggunaan) {
            return redirect()->to('/pelanggan')->with('error', 'Tidak bisa menghapus pelanggan karena masih ada data penggunaan air.');
        }

        $model->delete($id);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil dihapus.');
    }

    public function edit($id)
    {
        $model = new \App\Models\PelangganModel();
        $data['pelanggan'] = $model->find($id);
        $data['title'] = 'Edit Data Pelanggan';

        if (!$data['pelanggan']) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan.');
        }

        return view('admin/pelanggan/edit', $data);
    }

    public function update($id)
    {
        $model = new \App\Models\PelangganModel();

        $data = [
            'no_pelanggan' => $this->request->getPost('no_pelanggan'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'alamat'       => $this->request->getPost('alamat'),
            'no_hp'        => $this->request->getPost('no_hp'),
        ];

        $model->update($id, $data);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // spreadsheet export function
    public function export()
    {
        $model = new \App\Models\PelangganModel();
        $data = $model->where('role', 'pelanggan')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama Lengkap');
        $sheet->setCellValue('D1', 'No Pelanggan');
        $sheet->setCellValue('E1', 'Alamat');
        $sheet->setCellValue('F1', 'No HP');
        $sheet->setCellValue('G1', 'Tanggal Daftar');
        $sheet->setCellValue('H1', 'Waktu Ekspor');

        // Data isi
        $row = 2;
        $timestamp = date('Y-m-d H:i:s');
        foreach ($data as $index => $p) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $p['username']);
            $sheet->setCellValue('C' . $row, $p['nama_lengkap']);
            $sheet->setCellValue('D' . $row, $p['no_pelanggan']);
            $sheet->setCellValue('E' . $row, $p['alamat']);
            $sheet->setCellValueExplicit('F' . $row, $p['no_hp'], DataType::TYPE_STRING);

            // Format tanggal yang lebih jelas
            $tanggalDaftar = date('d-m-Y H:i', strtotime($p['created_at']));
            $sheet->setCellValue('G' . $row, $tanggalDaftar);

            $sheet->setCellValue('H' . $row, $timestamp);
            $row++;
        }

        // Otomatis sesuaikan lebar kolom
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Nama file ekspor
        $filename = 'data_pelanggan_' . date('Ymd_His') . '.xlsx';

        // Header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }





}
