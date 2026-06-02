<?php

namespace App\Controllers;

class Pengeluaran extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->send();
        }
    }

    // Menampilkan halaman utama kas pengeluaran
    public function index()
    {
        $builder = $this->db->table('pengeluaran_operasional po');
        $builder->select('po.*, c.NAMA_CABANG');
        $builder->join('cabang c', 'c.ID_CABANG = po.ID_CABANG', 'left');
        $builder->orderBy('po.TANGGAL_PENGELUARAN', 'DESC');

        $data = [
            'title'       => 'Pengeluaran Operasional | Teh Kota',
            'pengeluaran' => $builder->get()->getResultArray(),
            'cabang'      => $this->db->table('cabang')->get()->getResultArray()
        ];

        return view('pengeluaran/index', $data);
    }

    // Memproses penyimpanan data kas keluar
    public function simpan()
    {
        $id_cabang = $this->request->getPost('id_cabang');
        $nominal   = $this->request->getPost('nominal');
        $keterangan = $this->request->getPost('keterangan');

        $data = [
            'ID_PENGELUARAN'      => 'EXP-' . date('YmdHis'),
            'ID_CABANG'           => $id_cabang,
            'TANGGAL_PENGELUARAN' => date('Y-m-d H:i:s'),
            'NOMINAL'             => $nominal,
            'KETERANGAN'          => $keterangan
        ];

        $this->db->table('pengeluaran_operasional')->insert($data);

        return redirect()->to('/pengeluaran')->with('success', 'Catatan biaya operasional berhasil disimpan.');
    }

    // Menghapus catatan jika ada salah input
    public function hapus($id)
    {
        $this->db->table('pengeluaran_operasional')->where('ID_PENGELUARAN', $id)->delete();
        return redirect()->to('/pengeluaran')->with('success', 'Catatan pengeluaran berhasil dihapus.');
    }
}