<?php
namespace App\Models;
use CodeIgniter\Model;

class StokModel extends Model
{
    protected $table = 'stok_cabang';

    public function getStokPerCabang($id_cabang = null)
    {
        $builder = $this->db->table('stok_cabang');
        $builder->select('stok_cabang.*, menu.NAMA_MENU, cabang.NAMA_CABANG');
        $builder->join('menu', 'menu.ID_MENU = stok_cabang.ID_MENU');
        $builder->join('cabang', 'cabang.ID_CABANG = stok_cabang.ID_CABANG');
        
        if ($id_cabang) {
            $builder->where('stok_cabang.ID_CABANG', $id_cabang);
        }

        return $builder->get()->getResultArray();
    }

    public function updateStok($id_cabang, $id_menu, $jumlah) {
    return $this->db->table('stok_cabang')
        ->set('JUMLAH_STOK', 'JUMLAH_STOK + ' . (int)$jumlah, FALSE) // Menambah stok yang ada
        ->where('ID_CABANG', $id_cabang)
        ->where('ID_MENU', $id_menu)
        ->update();
}
}