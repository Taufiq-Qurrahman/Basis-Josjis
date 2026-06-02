<?php

namespace App\Controllers;

class Mitra extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $mitra = $this->db->table('mitra')->get()->getResultArray();

        $data = [
            'title' => 'Data Master Mitra | Teh Kota',
            'mitra' => $mitra
        ];

        return view('master/mitra', $data);
    }

    public function simpan()
    {
        $data = [
            'ID_MITRA'       => $this->request->getPost('id_mitra'),
            'NAMA_MITRA'     => $this->request->getPost('nama_mitra'),
            'NO_TELP'        => $this->request->getPost('no_telp'),
            'ALAMAT'         => $this->request->getPost('alamat_tinggal'), 
            
            // SINKRONISASI: Ubah key database menjadi TANGGAL_GABUNG
            'TANGGAL_GABUNG' => $this->request->getPost('tgl_bergabung'),  
        ];

        $this->db->table('mitra')->insert($data);
        return redirect()->to('/mitra');
    }

    public function update()
    {
        $id = $this->request->getPost('id_mitra'); 
        
        $data = [
            'NAMA_MITRA'     => $this->request->getPost('nama_mitra'),
            'NO_TELP'        => $this->request->getPost('no_telp'),
            'ALAMAT'         => $this->request->getPost('alamat_tinggal'),
            
            // SINKRONISASI: Ubah key database menjadi TANGGAL_GABUNG
            'TANGGAL_GABUNG' => $this->request->getPost('tgl_bergabung'),
        ];

        $this->db->table('mitra')->where('ID_MITRA', $id)->update($data);
        return redirect()->to('/mitra');
    }

    public function profil($id)
    {
        $mitra = $this->db->table('mitra')->where('ID_MITRA', $id)->get()->getRowArray();

        if (empty($mitra)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Mitra tidak ditemukan.');
        }

        $cabangs = $this->db->table('cabang')
                          ->where('ID_MITRA_PEMILIK', $id)
                          ->get()->getResultArray();

        $data = [
            'title'   => 'Profil Mitra | Teh Kota',
            'mitra'   => $mitra,
            'cabangs' => $cabangs,
        ];

        return view('master/mitra_profil', $data);
    }

    public function hapus($id)
{
    // Menghapus data berdasarkan ID_MITRA yang dikirim melalui URL
    $this->db->table('mitra')->where('ID_MITRA', $id)->delete();
    
    // Set notifikasi sukses jika diperlukan, lalu kembalikan ke halaman utama mitra
    return redirect()->to('/mitra');
}
}