<?php

namespace App\Models;

use CodeIgniter\Model;

class CabangModel extends Model
{
    protected $table            = 'cabang';
    protected $primaryKey       = 'ID_CABANG';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'ID_CABANG', 
        'ALAMAT', 
        'NAMA_CABANG', 
        'NO_TELP', 
        'ID_MITRA_PEMILIK', 
        'KOTA', 
        'AVG_PENJUALAN_BULANAN'
    ];
}