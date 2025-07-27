<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\TagihanModel;
use App\Models\PenggunaanAirModel;
use App\Models\UserModel;

class Struk extends BaseController
{
    public function cetak($id_tagihan)
    {
        $tagihanModel = new TagihanModel();

        $tagihan = $tagihanModel
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->where('id_tagihan', $id_tagihan)
            ->first();

        if (!$tagihan) {
            return redirect()->to('/user/dashboard')->with('error', 'Data tidak ditemukan.');
        }

        // Load view sebagai HTML
        $html = view('user/struk_pdf', ['tagihan' => $tagihan]);

        // Inisialisasi Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();

        // Unduh file
        $dompdf->stream("Struk-Tagihan-{$tagihan['id_tagihan']}.pdf", ["Attachment" => true]);
    }
}
