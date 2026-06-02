<?php
namespace App\Controllers;
use App\Models\DashboardModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/auth');

        $model = new DashboardModel();
        
        $data = [
            'title'            => 'Dashboard Utama | Tehkota',
            'username'          => session()->get('username'),
            'role'              => session()->get('role'), // ✔️ FIX: Diubah dari user_type menjadi role
            'omzet'             => $model->getOmzetHariIni(),
            'total_cabang'      => $model->getTotalCabang(),
            'stok_warning'      => $model->getStokMenipis(),
            'pengiriman_aktif'  => $model->getStatusPengiriman(),
            'data_grafik'       => json_encode($model->getGrafikPenjualan()) 
        ];

        // Tambahkan $data sebagai parameter kedua
        return view('dashboard/index', $data);
    }
}