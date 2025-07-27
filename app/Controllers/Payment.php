<?php
namespace App\Controllers;

use App\Models\TagihanModel;
use App\Models\PenggunaanAirModel;
use App\Models\UserModel;

class Payment extends BaseController
{
    public function index($id_tagihan)
    {
        helper('text');
        $tagihanModel = new TagihanModel();
        $userModel = new UserModel();

        $tagihan = $tagihanModel
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->where('id_tagihan', $id_tagihan)
            ->first();

        if (!$tagihan) {
            return redirect()->back()->with('error', 'Tagihan tidak ditemukan.');
        }

        // Setup Midtrans
        \Midtrans\Config::$serverKey = '< YOUR_SERVER_KEY >'; // Ganti dengan server key Anda
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'INV-' . $tagihan['id_tagihan'] . '-' . random_string('alnum', 5),
                'gross_amount' => (int)$tagihan['total_tagihan']
            ],
            'customer_details' => [
                'first_name' => $tagihan['nama_lengkap'],
                'email' => $tagihan['email'] ?? 'test@email.com',
            ]
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return view('user/payment', [
            'snapToken' => $snapToken,
            'tagihan' => $tagihan
        ]);
    }


    public function finish()
    {
        $order_id = $this->request->getVar('order_id');

        // Pisahkan ID tagihan dari order_id yang formatnya: INV-61-fNqC6
        $parts = explode('-', $order_id);
        $id_tagihan = isset($parts[1]) ? $parts[1] : null;

        if (!$id_tagihan) {
            return redirect()->to('/user/dashboard')->with('error', 'ID Tagihan tidak valid.');
        }

        $tagihanModel = new \App\Models\TagihanModel();

        $tagihan = $tagihanModel
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->where('id_tagihan', $id_tagihan)
            ->first();

        if (!$tagihan) {
            return redirect()->to('/user/dashboard')->with('error', 'Data tagihan tidak ditemukan.');
        }

        // Optional: update status menjadi "Lunas"
        $tagihanModel->update($id_tagihan, ['status' => 'Lunas']);

        return view('user/payment_finish', [
            'tagihan' => $tagihan
        ]);
    }

    


}
