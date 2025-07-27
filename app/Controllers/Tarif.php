<?php

namespace App\Controllers;

use App\Models\TarifAirModel;

class Tarif extends BaseController
{
    public function index()
    {
        $model = new TarifAirModel();
        $tarif = $model->first();

        return view('admin/tarif/index', ['tarif' => $tarif]);
    }

    public function update()
    {
        $harga = $this->request->getPost('harga_per_m3');
        $model = new TarifAirModel();

        $existing = $model->first();
        if ($existing) {
            // ganti 'id' ke 'id_tarif'
            $model->update($existing['id_tarif'], ['harga_per_m3' => $harga]);
        } else {
            $model->insert(['harga_per_m3' => $harga]);
        }

        return redirect()->to('/tarif')->with('success', 'Tarif berhasil diperbarui.');
    }
}
