<?php
namespace App\Controllers;
use App\Models\CabangModel;

class MasterCabang extends BaseController
{
    protected $cabangModel;

    public function __construct()
    {
        $this->cabangModel = new CabangModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Kelola Cabang | Tehkota Master',
            'cabang' => $this->cabangModel->findAll()
        ];
        return view('master/cabang/index', $data);
    }

    public function simpan()
    {
        $this->cabangModel->save([
            'ID_CABANG'   => $this->request->getPost('id_cabang'),
            'NAMA_CABANG' => $this->request->getPost('nama_cabang'),
            'ALAMAT'      => $this->request->getPost('alamat'),
            'NO__TELP'    => $this->request->getPost('no_telp')
        ]);
        return redirect()->to('/master/cabang');
    }

    public function hapus($id)
    {
        $this->cabangModel->delete($id);
        return redirect()->to('/master/cabang');
    }
    
}