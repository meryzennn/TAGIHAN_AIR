<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['username', 'password', 'role', 'nama_lengkap', 'alamat', 'no_hp', 'no_pelanggan'];
}
