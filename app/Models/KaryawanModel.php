<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table            = 'karyawan';
    protected $primaryKey       = 'ID_KARYAWAN';
    protected $returnType       = 'array';
    
    // Daftarkan semua kolom database agar diizinkan untuk di-insert/update
    protected $allowedFields    = [
        'ID_KARYAWAN', 
        'ID_CABANG', 
        'NAMA_KARYAWAN', 
        'NIK', 
        'JABATAN', 
        'GAJI', 
        'NO_TELEPON', 
        'ALAMAT_RUMAH', 
        'STATUS_KONTRAK'
    ];
}