<?php

namespace App\Models;

use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table = 'tagihan';
    protected $primaryKey = 'id_tagihan';
    protected $allowedFields = ['id_penggunaan', 'total_tagihan', 'status', 'created_at'];
    protected $useTimestamps = false;


    // Tambahan fungsi untuk laporan bulanan
    public function getRekapBulanan()
    {
        return $this->db->table('tagihan')
            ->select('
                MONTH(penggunaan_air.tanggal_pencatatan) AS bulan,
                COUNT(DISTINCT users.id_user) AS total_pelanggan,
                SUM(penggunaan_air.meter_akhir - penggunaan_air.meter_awal) AS total_pemakaian,
                SUM(tagihan.total_tagihan) AS total_tagihan,
                SUM(CASE WHEN tagihan.status = "Lunas" THEN 1 ELSE 0 END) AS jumlah_lunas,
                SUM(CASE WHEN tagihan.status = "Belum Dibayar" THEN 1 ELSE 0 END) AS jumlah_belum
            ')
            ->join('penggunaan_air', 'penggunaan_air.id_penggunaan = tagihan.id_penggunaan')
            ->join('users', 'users.id_user = penggunaan_air.id_user')
            ->groupBy('bulan')
            ->get()->getResultArray();
    }
}
