<?php

namespace App\Controllers;

class MasterCabang extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    // 1. TAMPILKAN TABEL UTAMA DAFTAR CABANG
    public function index()
    {
        $db = \Config\Database::connect();

        // Mengambil seluruh data cabang beserta rata-rata penjualan per bulan masing-masing cabang secara spesifik
        $data['cabang'] = $db->query("
            SELECT c.*, 
                   COALESCE(trx_bulanan.avg_nota_cabang, 0) * 15000 AS AVG_PENJUALAN_BULANAN
            FROM cabang c
            LEFT JOIN (
                SELECT sub.ID_CABANG, AVG(sub.total_nota) AS avg_nota_cabang
                FROM (
                    SELECT k.ID_CABANG, MONTH(p.TANGGAL_PENJUALAN) as bln, YEAR(p.TANGGAL_PENJUALAN) as thn, COUNT(p.ID_PENJUALAN) as total_nota
                    FROM penjualan p
                    JOIN karyawan k ON p.ID_KARIAWAN = k.ID_KARYAWAN
                    GROUP BY k.ID_CABANG, MONTH(p.TANGGAL_PENJUALAN), YEAR(p.TANGGAL_PENJUALAN)
                ) sub
                GROUP BY sub.ID_CABANG
            ) trx_bulanan ON c.ID_CABANG = trx_bulanan.ID_CABANG
        ")->getResultArray();

        $data['mitra'] = $db->table('mitra')->get()->getResultArray();
        $data['is_form'] = false; // Menampilkan tabel list utama

        // PERBAIKAN: Diarahkan ke folder master/cabang
        return view('master/cabang', $data);
    }

    // 2. DETAIL PROFIL OUTLET & LOG OPERASIONAL (COMMAND CENTER VERTIKAL)
    public function profil($id)
    {
        $db = \Config\Database::connect();

        // 1. Detail Identitas Cabang & Mitra Pemilik
        $data['cabang'] = $db->table('cabang c')
                             ->select('c.*, m.NAMA_MITRA, m.NO_TELP as TELP_MITRA, m.ALAMAT as ALAMAT_MITRA')
                             ->join('mitra m', 'c.ID_MITRA_PEMILIK = m.ID_MITRA', 'left')
                             ->where('c.ID_CABANG', $id)
                             ->get()->getRowArray();

        if (!$data['cabang']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Cabang Tidak Ditemukan");
        }

        // 2. Daftar Karyawan Cabang
        $data['karyawan'] = $db->table('karyawan')->where('ID_CABANG', $id)->get()->getResultArray();

        // 3. Penjualan Bulanan Cabang (Omset Makro)
        $data['penjualan_bulanan'] = $db->query("
            SELECT MONTHNAME(p.TANGGAL_PENJUALAN) as bulan, YEAR(p.TANGGAL_PENJUALAN) as tahun, 
                   COUNT(p.ID_PENJUALAN) * 15000 AS total_omset
            FROM penjualan p
            JOIN karyawan k ON p.ID_KARIAWAN = k.ID_KARYAWAN
            WHERE k.ID_CABANG = ?
            GROUP BY MONTH(p.TANGGAL_PENJUALAN), YEAR(p.TANGGAL_PENJUALAN)
            ORDER BY YEAR(p.TANGGAL_PENJUALAN) DESC, MONTH(p.TANGGAL_PENJUALAN) DESC
        ", [$id])->getResultArray();

        // 4. Penjualan Harian Terakhir Cabang (Omset Mikro 7 hari terakhir)
        $data['penjualan_harian'] = $db->query("
            SELECT p.TANGGAL_PENJUALAN, COUNT(p.ID_PENJUALAN) as jumlah_transaksi, 
                   COUNT(p.ID_PENJUALAN) * 15000 AS omset_harian
            FROM penjualan p
            JOIN karyawan k ON p.ID_KARIAWAN = k.ID_KARYAWAN
            WHERE k.ID_CABANG = ?
            GROUP BY p.TANGGAL_PENJUALAN
            ORDER BY p.TANGGAL_PENJUALAN DESC LIMIT 7
        ", [$id])->getResultArray();

        // 5. Daftar Supplier Terafiliasi Cabang
        $data['supplier'] = $db->table('supplier')->where('ID_CABANG', $id)->get()->getResultArray();

        // 6. Data Komplain Pelanggan Terhadap Cabang
        // 6. Mengambil data dari tabel pembeli yang sebenarnya ada di database Anda
$data['komplain'] = $db->table('pembeli')->limit(3)->get()->getResultArray(); 

        // KODE PERBAIKAN (Mengarahkan ke file profil khusus yang sudah kita buat):
return view('master/cabang_profil', $data);
    }

    // 3. TAMPILKAN FORM TAMBAH CABANG
    public function tambah()
    {
        $mitra = $this->db->table('mitra')->get()->getResultArray();

        $data = [
            'title'   => 'Tambah Cabang | Teh Kota',
            'mitra'   => $mitra,
            'is_form' => true // Menampilkan form input
        ];

        // PERBAIKAN: Diarahkan ke folder master/cabang
        return view('master/cabang', $data);
    }

    // 4. TAMPILKAN FORM EDIT CABANG
    public function edit($id)
    {
        $cabang = $this->db->table('cabang')->where('ID_CABANG', $id)->get()->getRowArray();
        $mitra  = $this->db->table('mitra')->get()->getResultArray();
        
        $karyawan_existing = $this->db->table('karyawan')->where('ID_CABANG', $id)->get()->getResultArray();

        if (empty($cabang)) {
            return redirect()->to(base_url('cabang'))->with('error', 'Data cabang tidak ditemukan.');
        }

        $data = [
            'title'             => 'Edit Cabang | Teh Kota',
            'cabang'            => $cabang,
            'mitra'             => $mitra,
            'karyawan_existing' => $karyawan_existing,
            'is_form'           => true // Menampilkan form input
        ];

        // PERBAIKAN: Diarahkan ke folder master/cabang
        return view('master/cabang', $data);
    }

    // 5. PROSES SIMPAN DATA TAMBAH
    public function simpanTambah()
    {
        $id_cabang = $this->request->getPost('ID_CABANG');

        $dataCabang = [
            'ID_CABANG'             => $id_cabang,
            'NAMA_CABANG'           => $this->request->getPost('NAMA_CABANG'),
            'ALAMAT'                => $this->request->getPost('ALAMAT'),
            'NO_TELP'               => $this->request->getPost('NO_TELP'), 
            'ID_MITRA_PEMILIK'      => $this->request->getPost('ID_MITRA_PEMILIK'),
            'KOTA'                  => $this->request->getPost('KOTA'),
            'AVG_PENJUALAN_BULANAN' => $this->request->getPost('AVG_PENJUALAN_BULANAN'),
        ];

        $this->db->transStart();
        $this->db->table('cabang')->insert($dataCabang);

        $karyawanList = $this->request->getPost('karyawan');
        if (!empty($karyawanList) && is_array($karyawanList)) {
            foreach ($karyawanList as $kar) {
                if (!empty($kar['ID_KARYAWAN']) && !empty($kar['NAMA_KARYAWAN'])) {
                    $this->db->table('karyawan')->insert([
                        'ID_KARYAWAN'    => $kar['ID_KARYAWAN'],
                        'ID_CABANG'      => $id_cabang,
                        'NAMA_KARYAWAN'  => $kar['NAMA_KARYAWAN'],
                        'NIK'            => $kar['NIK'],
                        'JABATAN'        => $kar['JABATAN'],
                        'GAJI'           => $kar['GAJI'],
                        'NO_TELEPON'     => $kar['NO_TELEPON'],
                        'ALAMAT_RUMAH'   => $kar['ALAMAT_RUMAH'],
                        'STATUS_KONTRAK' => 'Kontrak'
                    ]);
                }
            }
        }
        $this->db->transComplete();

        return redirect()->to(base_url('cabang'))->with('success', 'Data berhasil disimpan!');
    }

    // 6. PROSES SIMPAN DATA EDIT
    public function simpanEdit($id)
    {
        $dataCabang = [
            'NAMA_CABANG'           => $this->request->getPost('NAMA_CABANG'),
            'ALAMAT'                => $this->request->getPost('ALAMAT'),
            'NO_TELP'               => $this->request->getPost('NO_TELP'),
            'ID_MITRA_PEMILIK'      => $this->request->getPost('ID_MITRA_PEMILIK'),
            'KOTA'                  => $this->request->getPost('KOTA'),
            'AVG_PENJUALAN_BULANAN' => $this->request->getPost('AVG_PENJUALAN_BULANAN'),
        ];

        $this->db->transStart();
        $this->db->table('cabang')->where('ID_CABANG', $id)->update($dataCabang);
        $this->db->table('karyawan')->where('ID_CABANG', $id)->delete();

        $karyawanList = $this->request->getPost('karyawan');
        if (!empty($karyawanList) && is_array($karyawanList)) {
            foreach ($karyawanList as $kar) {
                if (!empty($kar['ID_KARYAWAN']) && !empty($kar['NAMA_KARYAWAN'])) {
                    $this->db->table('karyawan')->insert([
                        'ID_KARYAWAN'    => $kar['ID_KARYAWAN'],
                        'ID_CABANG'      => $id,
                        'NAMA_KARYAWAN'  => $kar['NAMA_KARYAWAN'],
                        'NIK'            => $kar['NIK'],
                        'JABATAN'        => $kar['JABATAN'],
                        'GAJI'           => $kar['GAJI'],
                        'NO_TELEPON'     => $kar['NO_TELEPON'],
                        'ALAMAT_RUMAH'   => $kar['ALAMAT_RUMAH'],
                        'STATUS_KONTRAK' => 'Kontrak'
                    ]);
                }
            }
        }
        $this->db->transComplete();

        return redirect()->to(base_url('cabang'))->with('success', 'Data berhasil diperbarui!');
    }

    // 7. PROSES HAPUS CABANG
    public function hapus($id)
    {
        $this->db->transStart();
        $this->db->table('karyawan')->where('ID_CABANG', $id)->delete();
        $this->db->table('cabang')->where('ID_CABANG', $id)->delete();
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->to(base_url('cabang'))->with('error', 'Gagal menghapus data cabang karena masalah relasi database.');
        }

        return redirect()->to(base_url('cabang'))->with('success', 'Data cabang dan karyawan terkait berhasil dihapus!');
    }
}