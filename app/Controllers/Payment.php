<?php

namespace App\Controllers;

use App\Models\TagihanModel;
use App\Models\PenggunaanAirModel;
use App\Models\UserModel;
use App\Models\TarifAirModel;

class Payment extends BaseController
{
    public function index($id_tagihan)
    {
        helper('text');

        $tagihanModel = new TagihanModel();
        $penggunaanModel = new PenggunaanAirModel();
        $tarifModel = new TarifAirModel();

        // Ambil data tagihan, penggunaan air, dan user
        $tagihan = $tagihanModel
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->where('id_tagihan', $id_tagihan)
            ->first();

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan.');
        }

        // Ambil tarif terbaru dari DB
        $tarif = $tarifModel->first();
        $hargaPerM3 = $tarif['harga_per_m3'] ?? 2500;

        // Hitung ulang total tagihan berdasarkan meter
        $total_pemakaian = $tagihan['meter_akhir'] - $tagihan['meter_awal'];
        $total_tagihan = $total_pemakaian * $hargaPerM3;

        // Update total_tagihan ke database jika berbeda
        if ($tagihan['total_tagihan'] != $total_tagihan) {
            $tagihanModel->update($id_tagihan, ['total_tagihan' => $total_tagihan]);
        }

        // Setup Midtrans
        \Midtrans\Config::$serverKey = 'Mid-server-bPaPrLKjtvYHU9tJR7K1umxV';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . $tagihan['id_tagihan'] . '-' . random_string('alnum', 5),
                'gross_amount' => $total_tagihan
            ],
            'customer_details' => [
                'first_name' => $tagihan['nama_lengkap'],
                'email' => $tagihan['email'] ?? 'test@email.com',
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('user/payment', [
            'snapToken' => $snapToken,
            'tagihan' => $tagihan,
            'total_tagihan' => $total_tagihan
        ]);
    }

    public function finish()
    {
        $order_id = $this->request->getVar('order_id');
        $parts = explode('-', $order_id);
        $id_tagihan = isset($parts[1]) ? $parts[1] : null;

        if (!$id_tagihan) {
            return redirect()->to('/user/dashboard')->with('error', 'ID Tagihan tidak valid.');
        }

        $tagihanModel = new TagihanModel();

        $tagihan = $tagihanModel
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->where('id_tagihan', $id_tagihan)
            ->first();

        if (!$tagihan) {
            return redirect()->to('/user/dashboard')->with('error', 'Data tagihan tidak ditemukan.');
        }

        // Update status menjadi Lunas
        $tagihanModel->update($id_tagihan, ['status' => 'Lunas']);

        return view('user/payment_finish', [
            'tagihan' => $tagihan
        ]);
    }
}
