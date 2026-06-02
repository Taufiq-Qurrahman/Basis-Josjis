<?php

namespace App\Controllers;

class Komplain extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->send();
        }
    }

    public function index()
    {
        $builder = $this->db->table('komplain k');
        $builder->select('k.*, p.NAMA_PEMBELI, c.NAMA_CABANG');
        $builder->join('pembeli p', 'p.ID_PEMBELI = k.ID_PEMBELI', 'left');
        $builder->join('cabang c', 'c.ID_CABANG = k.ID_CABANG', 'left');
        $builder->orderBy('k.TANGGAL_KOMPLAIN', 'DESC');
        $komplain = $builder->get()->getResultArray();

        $pembeli = $this->db->table('pembeli')->get()->getResultArray();
        $cabang = $this->db->table('cabang')->get()->getResultArray();

        $data = [
            'title'    => 'Keluhan / Komplain Pelanggan | Teh Kota',
            'komplain' => $komplain,
            'pembeli'  => $pembeli,
            'cabang'   => $cabang
        ];

        return view('komplain/index', $data);
    }

    public function simpan()
    {
        $data = [
            'ID_PEMBELI'       => $this->request->getPost('id_pembeli'),
            'ID_CABANG'        => $this->request->getPost('id_cabang'),
            'ISI_KOMPLAIN'     => $this->request->getPost('isi_komplain'),
            'TANGGAL_KOMPLAIN' => date('Y-m-d H:i:s'),
            'STATUS'           => $this->request->getPost('status') ?? 'Pending',
        ];

        $this->db->table('komplain')->insert($data);

        return redirect()->to('/komplain')->with('success', 'Data keluhan/komplain berhasil disimpan!');
    }

    public function update()
    {
        $id_komplain = $this->request->getPost('id_komplain');

        $data = [
            'ID_PEMBELI'   => $this->request->getPost('id_pembeli'),
            'ID_CABANG'    => $this->request->getPost('id_cabang'),
            'ISI_KOMPLAIN' => $this->request->getPost('isi_komplain'),
            'STATUS'       => $this->request->getPost('status')
        ];

        $this->db->table('komplain')->where('ID_KOMPLAIN', $id_komplain)->update($data);

        return redirect()->to('/komplain')->with('success', 'Data keluhan/komplain berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $this->db->table('komplain')->where('ID_KOMPLAIN', $id)->delete();
        return redirect()->to('/komplain')->with('success', 'Data keluhan/komplain berhasil dihapus!');
    }
}
