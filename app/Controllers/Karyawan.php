<?php

namespace App\Controllers;

class Karyawan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // JOIN karyawan dengan cabang untuk mendapatkan NAMA_CABANG penempatan
        $karyawan = $this->db->table('karyawan k')
            ->select('k.*, c.NAMA_CABANG')
            ->join('cabang c', 'c.ID_CABANG = k.ID_CABANG', 'left')
            ->get()->getResultArray();

        // Mengambil semua data cabang untuk pilihan dropdown di modal
        $cabang = $this->db->table('cabang')->get()->getResultArray();

        $data = [
            'title'    => 'Data Master Karyawan | Teh Kota',
            'karyawan' => $karyawan,
            'cabang'   => $cabang
        ];

        return view('master/karyawan', $data);
    }

    public function simpan()
    {
        $data = [
            'ID_KARYAWAN'   => $this->request->getPost('id_karyawan'),
            'NAMA_KARYAWAN' => $this->request->getPost('nama_karyawan'),
            'JABATAN'       => $this->request->getPost('jabatan'),
            
            // PERBAIKAN: Mengubah key array menjadi NO_TELEPON sesuai dengan kolom database aktual
            'NO_TELEPON'    => $this->request->getPost('nomer_telepon'),
            
            'GAJI'          => $this->request->getPost('gaji'),
            'ID_CABANG'     => $this->request->getPost('id_cabang'),
        ];

        $this->db->table('karyawan')->insert($data);
        return redirect()->to('/karyawan');
    }

    public function update()
    {
        // Amankan ID Karyawan yang tipenya hidden input
        $id = $this->request->getPost('id_karyawan'); 
        
        $data = [
            'NAMA_KARYAWAN'  => $this->request->getPost('nama_karyawan'),
            'NIK'            => $this->request->getPost('nik'),
            'JABATAN'        => $this->request->getPost('jabatan'),
            
            // PERBAIKAN: Mengubah key array menjadi NO_TELEPON sesuai dengan kolom database aktual
            'NO_TELEPON'     => $this->request->getPost('nomer_telepon'),
            
            'ALAMAT_RUMAH'   => $this->request->getPost('alamat_rumah'),
            'GAJI'           => $this->request->getPost('gaji'),
            'STATUS_KONTRAK' => $this->request->getPost('status_kontrak'),
            'ID_CABANG'      => $this->request->getPost('id_cabang'),
        ];

        $this->db->table('karyawan')->where('ID_KARYAWAN', $id)->update($data);
        return redirect()->to('/karyawan');
    }

    public function hapus($id)
    {
        $this->db->table('karyawan')->where('ID_KARYAWAN', $id)->delete();
        return redirect()->to('/karyawan');
    }
}