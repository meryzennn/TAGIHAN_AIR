<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifAirModel extends Model
{
    protected $table = 'tarif_air';
    protected $primaryKey = 'id_tarif';
    protected $allowedFields = ['harga_per_m3', 'berlaku_mulai', 'created_at'];
    protected $useTimestamps = false;

}
