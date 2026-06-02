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

            // 4. Update/Daftarkan Pembeli & Tambah Poin secara instan
            $id_pembeli = $dataTransaksi['id_pembeli'];
            $nama_pelanggan = trim($dataTransaksi['nama_pelanggan'] ?? '');
            $poinTambahan = floor($dataTransaksi['total'] / 10000);

            if (empty($id_pembeli) && !empty($nama_pelanggan) && strcasecmp($nama_pelanggan, 'Umum') !== 0 && $poinTambahan > 0) {
                // Cari pembeli dengan nama tersebut
                $existing = $db->table('pembeli')
                               ->where('LOWER(TRIM(NAMA_PEMBELI))', strtolower($nama_pelanggan))
                               ->get()->getRow();
                if ($existing) {
                    $id_pembeli = $existing->ID_PEMBELI;
                } else {
                    // Ambil ID Pembeli Terbesar untuk increment
                    $maxPbl = $db->query("SELECT ID_PEMBELI FROM pembeli ORDER BY ID_PEMBELI DESC LIMIT 1")->getRow();
                    $newNum = 1;
                    if ($maxPbl) {
                        preg_match('/\d+/', $maxPbl->ID_PEMBELI, $matches);
                        if (!empty($matches[0])) {
                            $newNum = ((int)$matches[0]) + 1;
                        }
                    }
                    $id_pembeli = 'PBL-' . str_pad($newNum, 3, '0', STR_PAD_LEFT);

                    $db->table('pembeli')->insert([
                        'ID_PEMBELI'            => $id_pembeli,
                        'NAMA_PEMBELI'          => $nama_pelanggan,
                        'NOMER_TELEPON_PEMBELI' => '',
                        'POINT'                 => 0
                    ]);
                }

                // Update ID_PEMBELI di transaksi penjualan agar tercatat
                $db->table('penjualan')
                   ->where('ID_PENJUALAN', $dataTransaksi['id_penjualan'])
                   ->update(['ID_PEMBELI' => $id_pembeli]);
            }

            if (!empty($id_pembeli) && $poinTambahan > 0) {
                $db->query("UPDATE pembeli SET POINT = POINT + ? WHERE TRIM(ID_PEMBELI) = TRIM(?)", [
                    (int)$poinTambahan, 
                    $id_pembeli
                ]);
            }

            return true;
        } catch (\Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Kasir Checkout Error: ' . $e->getMessage());
            throw $e;
        }
    }
}