<?php

namespace App\Controllers;

class Supplier extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // JOIN supplier dengan cabang untuk mendapatkan NAMA_CABANG
        $supplier = $this->db->table('supplier s')
            ->select('s.*, c.NAMA_CABANG')
            ->join('cabang c', 'c.ID_CABANG = s.ID_CABANG', 'left')
            ->get()->getResultArray();

        // Mengambil data cabang untuk pilihan dropdown di modal
        $cabang = $this->db->table('cabang')->get()->getResultArray();

        $data = [
            'title'    => 'Data Supplier | Teh Kota',
            'supplier' => $supplier,
            'cabang'   => $cabang
        ];

        return view('master/supplier', $data);
    }

    public function simpan()
    {
        // Deteksi dinamis kolom telepon yang ada di database kamu agar aman dari error #1054
        $fields = array_map('strtoupper', $this->db->getFieldNames('supplier'));
        $kolom_telp = in_array('NO_TELP', $fields) ? 'NO_TELP' : (in_array('NO_TELEPON', $fields) ? 'NO_TELEPON' : 'NOMER_TELEPON');

        $data = [
            'ID_SUPPLIER'   => $this->request->getPost('id_supplier'),
            'NAMA_SUPPLIER' => $this->request->getPost('nama_supplier'),
            'BARANG_SUPPLY' => $this->request->getPost('barang_supply'),
            'ID_CABANG'     => $this->request->getPost('id_cabang'),
            $kolom_telp     => $this->request->getPost('no_telp'),
        ];

        // Jika kolom ALAMAT ada di database, masukkan nilainya
        if (in_array('ALAMAT', $fields)) {
            $data['ALAMAT'] = $this->request->getPost('alamat');
        }

        $this->db->table('supplier')->insert($data);
        return redirect()->to('/supplier');
    }

    public function update()
    {
        $id = $this->request->getPost('id_supplier');
        
        $fields = array_map('strtoupper', $this->db->getFieldNames('supplier'));
        $kolom_telp = in_array('NO_TELP', $fields) ? 'NO_TELP' : (in_array('NO_TELEPON', $fields) ? 'NO_TELEPON' : 'NOMER_TELEPON');

        $data = [
            'NAMA_SUPPLIER' => $this->request->getPost('nama_supplier'),
            'BARANG_SUPPLY' => $this->request->getPost('barang_supply'),
            'ID_CABANG'     => $this->request->getPost('id_cabang'),
            $kolom_telp     => $this->request->getPost('no_telp'),
        ];

        if (in_array('ALAMAT', $fields)) {
            $data['ALAMAT'] = $this->request->getPost('alamat');
        }

        $this->db->table('supplier')->where('ID_SUPPLIER', $id)->update($data);
        return redirect()->to('/supplier');
    }

    public function hapus($id)
    {
        $this->db->table('supplier')->where('ID_SUPPLIER', $id)->delete();
        return redirect()->to('/supplier');
    }
}