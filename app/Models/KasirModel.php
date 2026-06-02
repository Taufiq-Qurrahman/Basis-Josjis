<?php
namespace App\Models;
use CodeIgniter\Model;

class KasirModel extends Model
{
    public function prosesCheckout($dataTransaksi, $keranjang)
    {
        $db = \Config\Database::connect();
        
        try {
            // KITA MATIKAN transStart() AGAR TIDAK ADA DEADLOCK/MACET DI XAMPP
            // Eksekusi akan dilakukan secara tembak langsung (Direct Query)

            // 1. Insert ke tabel penjualan
            $db->table('penjualan')->insert([
                'ID_PENJUALAN'      => $dataTransaksi['id_penjualan'],
                'ID_KARIAWAN'       => $dataTransaksi['id_karyawan'],
                'ID_PEMBELI'        => $dataTransaksi['id_pembeli'], 
                'ID_CABANG'         => $dataTransaksi['id_cabang'],
                'METODE_PEMBAYARAN' => $dataTransaksi['metode_bayar'],
                'TOTAL'             => $dataTransaksi['total'],
                'TANGGAL_PENJUALAN' => date('Y-m-d H:i:s')
            ]);

            // 2. Insert ke detail_penjualan & Potong Stok secara instan
            foreach ($keranjang as $item) {
                $idMenu = $item->id_menu ?? $item->id ?? null;

                if ($idMenu) {
                    $db->table('detail_penjualan')->insert([
                        'ID_PENJUALAN'     => $dataTransaksi['id_penjualan'],
                        'ID_MENU'          => $idMenu,
                        'JUMLAH_PENJUALAN' => (int)$item->qty,
                        'HARGA_SATUAN'     => (float)$item->harga,
                        'SUBTOTAL'         => (int)$item->qty * (float)$item->harga
                    ]);

                    // Potong stok (Tanpa menunggu antrean transaksi lain)
                    $db->query("UPDATE stok_cabang SET JUMLAH_STOK = JUMLAH_STOK - ? WHERE ID_MENU = ? AND ID_CABANG = ?", [
                        (int)$item->qty, $idMenu, $dataTransaksi['id_cabang']
                    ]);

                    $db->query("UPDATE menu SET STOK = STOK - ? WHERE ID_MENU = ?", [
                        (int)$item->qty, $idMenu
                    ]);
                }
            }

            // 3. Insert ke tabel pembayaran
            $db->table('pembayaran_penjualan')->insert([
                'ID_PEMBAYARAN_JUAL'  => 'PAY' . mt_rand(1000, 99999),
                'ID_PENJUALAN'        => $dataTransaksi['id_penjualan'],
                'ID_JENIS_PEMBAYARAN' => $dataTransaksi['metode_bayar'],
                'TANGGAL_BAYAR'       => date('Y-m-d H:i:s'),
                'JUMLAH_BAYAR'        => $dataTransaksi['total']
            ]);

            // 4. Update Point Pembeli secara instan
            if (!empty($dataTransaksi['id_pembeli'])) {
                $poinTambahan = floor($dataTransaksi['total'] / 10000); 
                if ($poinTambahan > 0) {
                    // Gunakan POINT (uppercase) sesuai struktur tabel
                    $db->query("UPDATE pembeli SET POINT = POINT + ? WHERE TRIM(ID_PEMBELI) = TRIM(?)", [
                        (int)$poinTambahan, 
                        $dataTransaksi['id_pembeli']
                    ]);
                }
            }

            return true;
        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Kasir Checkout Error: ' . $e->getMessage());
            throw $e;
        }
    }
}