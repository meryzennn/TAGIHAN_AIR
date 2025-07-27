<?php

namespace App\Controllers;

use App\Models\PenggunaanAirModel;
use App\Models\TagihanModel;
use App\Models\TarifAirModel;

class User extends BaseController
{
    public function index()
    {
        $id_user = session('id_user');

        $penggunaanModel = new PenggunaanAirModel();
        $tagihanModel    = new TagihanModel();
        $tarifModel      = new TarifAirModel();

        // Ambil penggunaan terakhir
        $last = $penggunaanModel
            ->where('id_user', $id_user)
            ->orderBy('tanggal_pencatatan', 'DESC')
            ->first();

        $pemakaian = $last ? $last['meter_akhir'] - $last['meter_awal'] : 0;

        // Ambil riwayat penggunaan + tagihan dengan pagination
        $perPage = 5;
        $page    = $this->request->getVar('page_riwayat') ?? 1;

        $builder = $tagihanModel
            ->select('penggunaan_air.*, tagihan.id_tagihan, tagihan.status')
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->where('penggunaan_air.id_user', $id_user)
            ->orderBy('penggunaan_air.tanggal_pencatatan', 'ASC');

        $riwayatData = $builder->paginate($perPage, 'riwayat');
        $pager       = $tagihanModel->pager;

        $riwayat = [];
        $belum_dibayar = 0;

        foreach ($riwayatData as $r) {
            $tarif = $tarifModel
                ->where('berlaku_mulai <=', $r['tanggal_pencatatan'])
                ->orderBy('berlaku_mulai', 'DESC')
                ->first();

            $harga = $tarif ? $tarif['harga_per_m3'] : 2500;
            $pemakaianRow = $r['meter_akhir'] - $r['meter_awal'];
            $tagihanTotal = $pemakaianRow * $harga;

            if ($r['status'] === 'Belum Dibayar') {
                $belum_dibayar += $tagihanTotal;
            }

            $riwayat[] = [
                'id_tagihan'         => $r['id_tagihan'],
                'tanggal_pencatatan' => $r['tanggal_pencatatan'],
                'meter_awal'         => $r['meter_awal'],
                'meter_akhir'        => $r['meter_akhir'],
                'pemakaian'          => $pemakaianRow,
                'harga_per_m3'       => $harga,
                'total_tagihan'      => $tagihanTotal,
                'status'             => $r['status']
            ];
        }

        return view('user/dashboard/index', [
            'pemakaian'     => $pemakaian,
            'tagihan_belum' => $belum_dibayar,
            'riwayat'       => $riwayat,
            'pager'         => $pager,
        ]);
    }
}
