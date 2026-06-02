<?php

namespace App\Controllers;

use App\Models\KasirModel; // Menggunakan model kasir Anda

class Kasir extends BaseController
{
    protected $db;

    public function __construct() 
    {
        $this->db = \Config\Database::connect();
    }
    
  public function index()
    {
        // 1. Ambil ID Cabang aktif dari session
        $id_cabang = session()->get('id_cabang') ?? session()->get('ID_CABANG');
        
        if (empty($id_cabang)) {
            $id_kariawan_session = session()->get('id_kariawan') ?? session()->get('id_karyawan');
            if (!empty($id_kariawan_session)) {
                $karyawanRow = $this->db->table('karyawan')->where('ID_KARIAWAN', $id_kariawan_session)->get()->getRowArray();
                if ($karyawanRow) {
                    $karyawanRow = array_change_key_case($karyawanRow, CASE_LOWER);
                    $id_cabang = $karyawanRow['id_cabang'] ?? 'CB01';
                }
            }
        }
        if (empty($id_cabang)) { $id_cabang = 'CB01'; }

        // 2. Ambil data menu utama
        $menuRaw = $this->db->table('menu')->get()->getResultArray();
        
        $data['menu'] = array_map(function($item) use ($id_cabang) {
            // AMBIL DATA STOK ASLI DARI TABEL MENU (Agar tidak hilang menjadi 0)
            $stokBawaanMenu = $item['STOK'] ?? $item['stok'] ?? 0;
            $idMenuAsli = $item['ID_MENU'] ?? $item['id_menu'] ?? null;
            
            // Ubah array key ke lowercase agar sinkron dengan halaman View Kasir
            $item = array_change_key_case($item, CASE_LOWER);
            
            // Set nilai awal dari stok tabel menu utama terlebih dahulu
            $item['stok'] = (int)$stokBawaanMenu; 

            // JIKA ada data pelacakan di tabel stok_cabang, gunakan nilai spesifik cabang tersebut
            if ($idMenuAsli && $id_cabang) {
                $stokRow = $this->db->table('stok_cabang')
                                    ->where('ID_MENU', $idMenuAsli)
                                    ->where('ID_CABANG', $id_cabang)
                                    ->get()
                                    ->getRowArray();
                
                if ($stokRow) {
                    $stokRow = array_change_key_case($stokRow, CASE_LOWER);
                    // Ambil jumlah_stok milik cabang, jika kosong gunakan stok bawaan menu
                    $item['stok'] = (int)($stokRow['jumlah_stok'] ?? $stokRow['stok'] ?? $stokBawaanMenu);
                }
            }
            
            return $item;
        }, $menuRaw);

        // 3. Ambil jenis pembayaran
        $bayarRaw = $this->db->table('jenis_pembayaran')->get()->getResultArray();
        $data['pembayaran'] = array_map(function($item) {
            return array_change_key_case($item, CASE_LOWER);
        }, $bayarRaw);

        // 4. Ambil data pelanggan
        $pembeliRaw = $this->db->table('pembeli')->get()->getResultArray();
        $data['pembeli_list'] = array_map(function($item) {
            return array_change_key_case($item, CASE_LOWER);
        }, $pembeliRaw);

        $data['title'] = "Kasir POS | Tehkota";
        return view('kasir/index', $data);
    }
public function simpan()
    {
        // 1. Validasi Keamanan Request AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Akses langsung tidak diizinkan.'
            ])->setStatusCode(403);
        }

        // 2. Tangkap Input Data JSON dari JavaScript
        $json = $this->request->getJSON();
        if (empty($json) || empty($json->items)) {
            return $this->response->setJSON([
                'status'  => 'error', 
                'message' => 'Keranjang belanja kosong.'
            ]);
        }

        // 3. AMBIL DATA SESSION & BERIKAN PROTEKSI JIKA KOSONG (NULL)
        // Memeriksa variasi penamaan huruf besar/kecil key session yang umum digunakan
        $id_karyawan = session()->get('ID_KARYAWAN') ?? session()->get('id_karyawan') ?? session()->get('ID_KARIAWAN') ?? session()->get('id_kariawan');
        $id_cabang   = session()->get('ID_CABANG') ?? session()->get('id_cabang');

        // SINKRONISASI DATABASE: Jika session kosong, gunakan data dummy valid dari web_tehkota.sql
        if (empty($id_karyawan)) {
            $id_karyawan = 'EMP-001'; // ID Andi Wijaya (Manager Outlet) yang ada di database Anda
        }
        if (empty($id_cabang)) {
            $id_cabang = 'FR-001';   // ID Cabang default yang aktif di database Anda
        }

        // 4. Siapkan Data Master Transaksi Penjualan
        $id_penjualan    = 'PJ' . date('dmy') . mt_rand(1000, 9999);
        $id_pembeli      = !empty($json->pembeli_id) ? $json->pembeli_id : null;
        $nama_pelanggan  = !empty($json->nama_pelanggan) ? trim($json->nama_pelanggan) : 'Umum';
        $metode_bayar    = !empty($json->pembayaran) ? $json->pembayaran : '1';

        // Hitung total belanja dari item keranjang secara manual untuk validasi
        $total = 0;
        foreach ($json->items as $item) {
            $total += ($item->qty * $item->harga);
        }

        $dataTransaksi = [
            'id_penjualan'   => $id_penjualan,
            'id_karyawan'    => $id_karyawan,
            'id_pembeli'     => $id_pembeli,
            'nama_pelanggan' => $nama_pelanggan,
            'id_cabang'      => $id_cabang,
            'metode_bayar'   => $metode_bayar,
            'total'          => $total
        ];

        // 5. Panggil Model Kasir untuk Eksekusi Query ke Database
        $kasirModel = new \App\Models\KasirModel();
        
        try {
            $proses = $kasirModel->prosesCheckout($dataTransaksi, $json->items);
            
            if ($proses) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'trx_id' => $id_penjualan
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal memproses query transaksi ke database.'
                ]);
            }
        } catch (\Exception $e) {
            // Menangkap pesan error asli jika ada kolom database yang bermasalah lagi
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Sistem Database Eror: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    
    }
}