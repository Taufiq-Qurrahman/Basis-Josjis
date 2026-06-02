<?php
namespace App\Controllers;
use App\Models\StokModel;
use App\Models\DashboardModel; // Untuk ambil daftar cabang

class Stok extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/auth');

        $stokModel = new StokModel();
        $dbModel = new \App\Models\DashboardModel();

        $data = [
            'title'   => 'Manajemen Stok Nasional | Tehkota',
            'stok'    => $stokModel->getStokPerCabang(),
            'cabang'  => $dbModel->db->table('cabang')->get()->getResultArray()
        ];

        return view('stok/index', $data);
    }

    public function update_cepat()
    {
        $stokModel = new StokModel();
        $id_cabang = $this->request->getPost('id_cabang');
        $id_menu   = $this->request->getPost('id_menu');
        $jumlah    = $this->request->getPost('jumlah');

        $simpan = $stokModel->updateStok($id_cabang, $id_menu, $jumlah);

        if ($simpan) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Stok diperbarui!']);
        }
    }
}