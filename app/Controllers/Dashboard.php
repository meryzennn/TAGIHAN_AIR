<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TagihanModel;
use App\Models\PenggunaanAirModel;

class Dashboard extends BaseController
{
    public function admin()
    {
        // Total Pelanggan
        $userModel = new UserModel();
        $totalPelanggan = $userModel->where('role', 'pelanggan')->countAllResults();

        // Tagihan Lunas dan Belum Dibayar
        $tagihanModel = new TagihanModel();
        $tagihanLunas = $tagihanModel->where('status', 'Lunas')->countAllResults();
        $tagihanBelum = $tagihanModel->where('status', 'Belum Dibayar')->countAllResults();

        // Grafik Pemakaian Air Bulanan
        $penggunaanModel = new PenggunaanAirModel();
        $builder = $penggunaanModel->select("MONTH(tanggal_pencatatan) as bulan, SUM(meter_akhir - meter_awal) as total")
            ->groupBy("bulan")
            ->orderBy("bulan", "ASC")
            ->limit(6); // 6 bulan terakhir

        $result = $builder->findAll();

        $bulan = [];
        $total = [];

        foreach ($result as $row) {
            $bulan[] = date('M', mktime(0, 0, 0, $row['bulan'], 10)); // misal Jan, Feb
            $total[] = (int) $row['total'];
        }

        return view('admin/dashboard/index', [
            'totalPelanggan' => $totalPelanggan,
            'tagihanLunas' => $tagihanLunas,
            'tagihanBelum' => $tagihanBelum,
            'bulan' => json_encode($bulan),
            'totalPemakaian' => json_encode($total)
        ]);
    }
}
