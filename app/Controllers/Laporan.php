<?php
namespace App\Controllers;

class Laporan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
        // Pengaman: Jika belum login, tendang ke halaman auth
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth')->send();
        }
    }

    // 1. HALAMAN UTAMA LAPORAN PENJUALAN
    public function index()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        // SINKRONISASI SQL: Mengambil data penjualan asli berdasarkan kolom database Anda
        $penjualan = $this->db->table('penjualan p')
            ->select('p.*, k.NAMA_KARYAWAN, c.NAMA_CABANG')
            ->join('karyawan k', 'k.ID_KARYAWAN = p.ID_KARIAWAN', 'left')
            ->join('cabang c', 'c.ID_CABANG = p.ID_CABANG', 'left')
            ->where('DATE(p.TANGGAL_PENJUALAN)', $tanggal)
            ->get()->getResultArray();

        // SINKRONISASI SQL: Menggunakan kolom 'TOTAL' sesuai isi database
        $totalOmzet = 0;
        foreach ($penjualan as $p) {
            $totalOmzet += $p['TOTAL'] ?? 0;
        }

        $data = [
            'title'      => "Laporan Penjualan | Teh Kota",
            'penjualan'  => $penjualan,
            'totalOmzet' => $totalOmzet,
            'tgl_pilih'  => $tanggal,
            'active_tab' => 'transaksi',
            'cabang'     => $this->db->table('cabang')->get()->getResultArray() 
        ];

        return view('laporan/index', $data);
    }

    // Fungsi AJAX Detail Penjualan
    public function detail($id)
    {
        $builder = $this->db->table('detail_penjualan dp');
        $builder->select('dp.*, m.NAMA_MENU');
        $builder->join('menu m', 'm.ID_MENU = dp.ID_MENU', 'left');
        $builder->where('dp.ID_PENJUALAN', $id);
        $detail = $builder->get()->getResultArray();

        return $this->response->setJSON($detail);
    }

    // 2. HALAMAN LAPORAN LABA RUGI
    public function labaRugi()
    {
        $bulan = $this->request->getGet('bulan') ?? date('Y-m');

        // SINKRONISASI SQL: Hitung total penjualan berdasarkan kolom 'TOTAL'
        $queryPenjualan = $this->db->table('penjualan')
            ->selectSum('TOTAL', 'total')
            ->where("DATE_FORMAT(TANGGAL_PENJUALAN, '%Y-%m')", $bulan)
            ->get()->getRowArray();
        $penjualanNominal = $queryPenjualan['total'] ?? 0;

        // SINKRONISASI SQL: Hitung pengeluaran dari tabel 'pengeluaran_operasional'
        $queryPengeluaran = $this->db->table('pengeluaran_operasional')
            ->selectSum('NOMINAL', 'total')
            ->where("DATE_FORMAT(TANGGAL_PENGELUARAN, '%Y-%m')", $bulan)
            ->get()->getRowArray();
        $pengeluaranNominal = $queryPengeluaran['total'] ?? 0;

        $data = [
            'title'             => 'Laporan Laba Rugi | Teh Kota',
            'bulan'             => $bulan,
            'total_penjualan'   => $penjualanNominal,
            'total_pengeluaran' => $pengeluaranNominal,
            'laba_rugi'         => $penjualanNominal - $pengeluaranNominal, // Rumus Laba Bersih
            'active_tab'        => 'labarugi',
            'cabang'            => $this->db->table('cabang')->get()->getResultArray()
        ];

        return view('laporan/laba_rugi', $data);
    }

    // 3. LAPORAN STOK INVENTORI
    public function stokInventori()
    {
        $stokData = $this->db->table('stok_cabang s')
                       ->select('s.*, m.NAMA_MENU, c.NAMA_CABANG')
                       ->join('menu m', 'm.ID_MENU = s.ID_MENU', 'left')
                       ->join('cabang c', 'c.ID_CABANG = s.ID_CABANG', 'left')
                       ->get()->getResultArray();

        $data = [
            'title'      => 'Laporan Stok Inventori | Teh Kota',
            'stok'       => $stokData,
            'active_tab' => 'stok',
            'cabang'     => $this->db->table('cabang')->get()->getResultArray()
        ];

        return view('laporan/stok_inventori', $data);
    }

    // 4. AUDIT LOG SYSTEM
    public function auditLog()
    {
        $fields = $this->db->getFieldNames('audit_logs');
        $builder = $this->db->table('audit_logs');

        if (in_array('TIMESTAMP', $fields)) {
            $builder->orderBy('TIMESTAMP', 'DESC');
        } elseif (in_array('timestamp', $fields)) {
            $builder->orderBy('timestamp', 'DESC');
        } elseif (in_array('created_at', $fields)) {
            $builder->orderBy('created_at', 'DESC');
        } elseif (in_array('waktu', $fields)) {
            $builder->orderBy('waktu', 'DESC');
        } elseif (in_array('TANGGAL', $fields)) {
            $builder->orderBy('TANGGAL', 'DESC');
        }

        $logs = $builder->limit(100)->get()->getResultArray();

        $data = [
            'title'      => 'Audit Log System | Teh Kota',
            'logs'       => $logs,
            'active_tab' => 'audit',
            'cabang'     => $this->db->table('cabang')->get()->getResultArray()
        ];

        return view('laporan/audit_log', $data);
    }
}