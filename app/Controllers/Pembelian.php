<?php

namespace App\Controllers;

class Pembelian extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->send();
        }
    }

    // Menampilkan Riwayat Pembelian ke Supplier
    public function index()
    {
        $builder = $this->db->table('pembelian p');
        $builder->select('p.*, s.NAMA_SUPPLIER, c.NAMA_CABANG');
        $builder->join('supplier s', 's.ID_SUPPLIER = p.ID_SUPPLIER', 'left');
        $builder->join('cabang c', 'c.ID_CABANG = p.ID_CABANG', 'left');
        $builder->orderBy('p.TANGGAL_PEMBELIAN', 'DESC');
        
        $data = [
            'title'     => 'Riwayat Pembelian Stok | Teh Kota',
            'pembelian' => $builder->get()->getResultArray()
        ];

        return view('pembelian/index', $data);
    }

    // Halaman Form Input Pembelian Baru
    public function tambah()
    {
        $data = [
            'title'     => 'Input Pembelian Stok Baru | Teh Kota',
            'supplier'  => $this->db->table('supplier')->get()->getResultArray(),
            'cabang'    => $this->db->table('cabang')->get()->getResultArray(),
            'menu'      => $this->db->table('menu')->get()->getResultArray() // Sebagai perwakilan item bahan baku/menu
        ];

        return view('pembelian/tambah', $data);
    }

    // Proses Simpan Transaksi ke Database
    public function simpan()
    {
        $id_supplier = $this->request->getPost('id_supplier');
        $id_cabang   = $this->request->getPost('id_cabang');
        $id_menu     = $this->request->getPost('id_menu');
        $jumlah      = $this->request->getPost('jumlah');
        $harga       = $this->request->getPost('harga');

        // Hitung total harga
        $total_bayar = $jumlah * $harga;
        $id_pembelian = 'BYR-' . date('YmdHis');

        // 1. Insert ke tabel induk 'pembelian'
        $dataPembelian = [
            'ID_PEMBELIAN'      => $id_pembelian,
            'ID_SUPPLIER'       => $id_supplier,
            'ID_CABANG'         => $id_cabang,
            'TANGGAL_PEMBELIAN' => date('Y-m-d H:i:s'),
            'TOTAL_PEMBELIAN'   => $total_bayar
        ];
        $this->db->table('pembelian')->insert($dataPembelian);

        // 2. Insert ke tabel 'detail_pembelian'
        $dataDetail = [
            'ID_PEMBELIAN' => $id_pembelian,
            'ID_MENU'      => $id_menu,
            'JUMLAH'       => $jumlah,
            'HARGA_BELI'   => $harga,
            'SUBTOTAL'     => $total_bayar
        ];
        $this->db->table('detail_pembelian')->insert($dataDetail);

        // 3. Update otomatis jumlah stok di tabel 'stok_cabang'
        $stokEksis = $this->db->table('stok_cabang')
                              ->where(['ID_CABANG' => $id_cabang, 'ID_MENU' => $id_menu])
                              ->get()->getRow();

        if ($stokEksis) {
            $stokBaru = $stokEksis->JUMLAH_STOK + $jumlah;
            $this->db->table('stok_cabang')
                     ->where(['ID_CABANG' => $id_cabang, 'ID_MENU' => $id_menu])
                     ->update(['JUMLAH_STOK' => $stokBaru]);
        } else {
            $this->db->table('stok_cabang')->insert([
                'ID_CABANG'   => $id_cabang,
                'ID_MENU'     => $id_menu,
                'JUMLAH_STOK' => $jumlah
            ]);
        }

        return redirect()->to('/pembelian')->with('success', 'Transaksi pembelian stok berhasil disimpan dan stok cabang diperbarui.');
    }
}