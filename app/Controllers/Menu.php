<?php

namespace App\Controllers;

class Menu extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
{
    // Mengambil seluruh baris dan kolom dari tabel menu
    $menu = $this->db->table('menu')->get()->getResultArray();

    $data = [
        'title' => 'Data Master Menu & Harga | Teh Kota',
        'menu'  => $menu
    ];

    return view('master/menu', $data);
}
public function update()
{
    $id = $this->request->getPost('id_menu');
    
    $data = [
        'NAMA_MENU'     => $this->request->getPost('nama_menu'),
        'DEFINISI_MENU' => $this->request->getPost('definisi_menu'),
        'STOK'          => $this->request->getPost('stok'),
        'HARGA_BELI'    => $this->request->getPost('harga_beli'),
        'HARGA'         => $this->request->getPost('harga_menu'), // <-- DIUBAH DI SINI (Ganti HARGA_MENU menjadi HARGA)
    ];

    $this->db->table('menu')->where('ID_MENU', $id)->update($data);
    return redirect()->to('/menu');
}
  public function simpan()
{
    $data = [
        'ID_MENU'        => $this->request->getPost('id_menu'),
        'NAMA_MENU'      => $this->request->getPost('nama_menu'),
        'HARGA'          => $this->request->getPost('harga_menu'), // <-- Pastikan ini juga HARGA
        'DEFINISI_MENU'  => $this->request->getPost('definisi_menu'),
        'STOK'           => $this->request->getPost('stok'),
        'HARGA_BELI'     => $this->request->getPost('harga_beli'),
    ];

    $this->db->table('menu')->insert($data);
    return redirect()->to('/menu');
}

    public function hapus($id)
    {
        $this->db->table('menu')->where('ID_MENU', $id)->delete();
        return redirect()->to('/menu');
    }
}