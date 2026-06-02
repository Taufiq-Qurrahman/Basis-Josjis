<?php

namespace App\Controllers;

class Pelanggan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Mengambil data sesuai tabel asli database pembeli
        $pelanggan = $this->db->table('pembeli')->get()->getResultArray();

        $data = [
            'title'     => 'Data Pelanggan | Teh Kota',
            'pelanggan' => $pelanggan
        ];

        return view('master/pelanggan', $data);
    }

    public function simpan()
    {
        $data = [
            'ID_PEMBELI'            => $this->request->getPost('id_pembeli') ?? $this->request->getPost('ID_PEMBELI'),
            'NAMA_PEMBELI'          => $this->request->getPost('nama_pembeli') ?? $this->request->getPost('NAMA_PEMBELI'),
            'NOMER_TELEPON_PEMBELI' => $this->request->getPost('nomer_telepon_pembeli') ?? $this->request->getPost('NOMER_TELEPON_PEMBELI'),
            'POINT'                 => $this->request->getPost('point') ?? $this->request->getPost('POINT') ?? 0,
        ];

        $this->db->table('pembeli')->insert($data);
        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil disimpan!');
    }

    public function update()
    {
        // Menangkap ID pembeli dengan aman (antisipasi huruf besar/kecil pada name input HTML)
        $id_pembeli = $this->request->getPost('id_pembeli') ?? $this->request->getPost('ID_PEMBELI');

        $data = [
            'NAMA_PEMBELI'          => $this->request->getPost('nama_pembeli') ?? $this->request->getPost('NAMA_PEMBELI'),
            'NOMER_TELEPON_PEMBELI' => $this->request->getPost('nomer_telepon_pembeli') ?? $this->request->getPost('NOMER_TELEPON_PEMBELI'),
            'POINT'                 => $this->request->getPost('point') ?? $this->request->getPost('POINT') ?? 0
        ];

        // Lakukan update berdasarkan ID_PEMBELI
        $this->db->table('pembeli')->where('ID_PEMBELI', $id_pembeli)->update($data);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $this->db->table('pembeli')->where('ID_PEMBELI', $id)->delete();
        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil dihapus!');
    }
}