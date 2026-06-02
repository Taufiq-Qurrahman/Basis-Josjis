<?php
namespace App\Models;
use CodeIgniter\Model;

class DashboardModel extends Model
{
    // Hitung total omzet hari ini
    public function getOmzetHariIni()
    {
        return $this->db->table('pembayaran_penjualan')
                    ->selectSum('JUMLAH_BAYAR')
                    ->where('DATE(TANGGAL_BAYAR)', date('Y-m-d'))
                    ->get()->getRow()->JUMLAH_BAYAR ?? 0;
    }

    // Hitung total seluruh cabang
    public function getTotalCabang()
    {
        return $this->db->table('cabang')->countAllResults();
    }

    // Hitung berapa item yang stoknya di bawah batas minimal (Alert)
    public function getStokMenipis()
    {
        return $this->db->table('stok_cabang')
                    ->where('JUMLAH_STOK <= BATAS_MINIMAL')
                    ->countAllResults();
    }

    // Ambil 5 transaksi penjualan terbaru
    public function getRecentSales()
    {
        return $this->db->table('penjualan')
                    ->select('penjualan.*, cabang.NAMA_CABANG, pembeli.NAMA_PEMBELI')
                    ->join('cabang', 'cabang.ID_CABANG = penjualan.ID_CABANG')
                    ->join('pembeli', 'pembeli.ID_PEMBELI = penjualan.ID_PEMBELI')
                    ->orderBy('TANGGAL_PENJUALAN', 'DESC')
                    ->limit(5)
                    ->get()->getResultArray();
    }

    // Mengambil status pengiriman yang belum diterima
    public function getStatusPengiriman()
    {
        return $this->db->table('pengiriman_stok')
            ->select('pengiriman_stok.*, asal.NAMA_CABANG as ASAL, tujuan.NAMA_CABANG as TUJUAN')
            ->join('cabang as asal', 'asal.ID_CABANG = pengiriman_stok.ID_CABANG_ASAL')
            ->join('cabang as tujuan', 'tujuan.ID_CABANG = pengiriman_stok.ID_CABANG_TUJUAN')
            ->where('STATUS_KIRIM !=', 'Diterima')
            ->orderBy('TANGGAL_KIRIM', 'DESC')
            ->get()->getResultArray();
    }

    // Mengambil data untuk Grafik Penjualan (Total per Cabang)
    public function getGrafikPenjualan()
    {
        return $this->db->table('penjualan')
            ->select('cabang.NAMA_CABANG, SUM(penjualan.TOTAL) as TOTAL_PENJUALAN')
            ->join('cabang', 'cabang.ID_CABANG = penjualan.ID_CABANG')
            ->groupBy('penjualan.ID_CABANG')
            ->get()->getResultArray();
    }
}