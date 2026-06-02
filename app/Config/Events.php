<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

Events::on('DBQuery', static function (\CodeIgniter\Database\Query $query): void {
    // Avoid recursion!
    static $isLogging = false;
    if ($isLogging) {
        return;
    }

    $sql = trim($query->getQuery());
    
    // We only log WRITE operations (INSERT, UPDATE, DELETE)
    // Avoid logging on 'audit_logs', 'ci_sessions', and migrations
    if (preg_match('/^\s*(insert|update|delete)\b/i', $sql)) {
        // Extract table name
        $tableName = '';
        if (preg_match('/^\s*insert\s+(?:ignore\s+)?into\s+[`"]?(\w+)[`"]?/i', $sql, $matches)) {
            $tableName = $matches[1];
        } elseif (preg_match('/^\s*update\s+[`"]?(\w+)[`"]?/i', $sql, $matches)) {
            $tableName = $matches[1];
        } elseif (preg_match('/^\s*delete\s+from\s+[`"]?(\w+)[`"]?/i', $sql, $matches)) {
            $tableName = $matches[1];
        }

        if (empty($tableName) || in_array(strtolower($tableName), ['audit_logs', 'ci_sessions', 'migrations'])) {
            return;
        }

        $isLogging = true;
        try {
            $db = \Config\Database::connect();
            
            // Get current session user
            $session = \Config\Services::session();
            $idUser = $session->get('id_user') ?? $session->get('username') ?? null;
            
            // Request IP Address
            $request = \Config\Services::request();
            $ipAddress = is_cli() ? 'CLI' : $request->getIPAddress();
            
            // Construct AKSI description
            $aksi = '';
            $actionType = '';
            if (preg_match('/^\s*insert\b/i', $sql)) {
                $actionType = 'INSERT';
            } elseif (preg_match('/^\s*update\b/i', $sql)) {
                $actionType = 'UPDATE';
            } elseif (preg_match('/^\s*delete\b/i', $sql)) {
                $actionType = 'DELETE';
            }
            
            $router = is_cli() ? null : service('router');
            $controller = $router ? class_basename($router->controllerName()) : '';
            $method = $router ? $router->methodName() : '';
            
            $tableLower = strtolower($tableName);
            if ($tableLower === 'menu') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan menu baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data menu";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus menu";
            } elseif ($tableLower === 'cabang') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan cabang baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data cabang";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus cabang";
            } elseif ($tableLower === 'karyawan') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan karyawan baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data karyawan";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus karyawan";
            } elseif ($tableLower === 'pembeli') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan pelanggan baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data pelanggan";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus pelanggan";
            } elseif ($tableLower === 'supplier') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan supplier baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data supplier";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus supplier";
            } elseif ($tableLower === 'mitra') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan mitra baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah data mitra";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus mitra";
            } elseif ($tableLower === 'penjualan') {
                if ($actionType === 'INSERT') $aksi = "Melakukan transaksi penjualan";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah transaksi penjualan";
                elseif ($actionType === 'DELETE') $aksi = "Membatalkan/Menghapus transaksi penjualan";
            } elseif ($tableLower === 'pembelian') {
                if ($actionType === 'INSERT') $aksi = "Melakukan transaksi pembelian bahan baku";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah transaksi pembelian";
                elseif ($actionType === 'DELETE') $aksi = "Membatalkan/Menghapus transaksi pembelian";
            } elseif ($tableLower === 'pengeluaran_operasional') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan pengeluaran operasional";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah pengeluaran operasional";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus pengeluaran operasional";
            } elseif ($tableLower === 'stok_cabang') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan stok cabang baru";
                elseif ($actionType === 'UPDATE') $aksi = "Memperbarui stok cabang";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus stok cabang";
            } elseif ($tableLower === 'komplain') {
                if ($actionType === 'INSERT') $aksi = "Menambahkan komplain baru";
                elseif ($actionType === 'UPDATE') $aksi = "Mengubah status/data komplain";
                elseif ($actionType === 'DELETE') $aksi = "Menghapus data komplain";
            }
            
            if (empty($aksi)) {
                $aksi = "Melakukan {$actionType} pada tabel '{$tableName}'";
            }
            
            $idObj = '';
            if (!is_cli()) {
                if ($request->getPost('id_menu')) $idObj = ' ID: ' . $request->getPost('id_menu');
                elseif ($request->getPost('id_cabang')) $idObj = ' ID: ' . $request->getPost('id_cabang');
                elseif ($request->getPost('id_karyawan')) $idObj = ' ID: ' . $request->getPost('id_karyawan');
                elseif ($request->getPost('id_pembeli')) $idObj = ' ID: ' . $request->getPost('id_pembeli');
                elseif ($request->getPost('id_supplier')) $idObj = ' ID: ' . $request->getPost('id_supplier');
                elseif ($request->getPost('id_mitra')) $idObj = ' ID: ' . $request->getPost('id_mitra');
                elseif ($request->getPost('id_penjualan')) $idObj = ' ID: ' . $request->getPost('id_penjualan');
                elseif ($request->getPost('id_pembelian')) $idObj = ' ID: ' . $request->getPost('id_pembelian');
                elseif ($request->getPost('id_pengeluaran')) $idObj = ' ID: ' . $request->getPost('id_pengeluaran');
                
                if ($idObj) {
                    $aksi .= " ($idObj)";
                } else {
                    $uri = $request->getUri();
                    if ($actionType === 'DELETE' && $uri->getTotalSegments() >= 3) {
                        $lastSegment = $uri->getSegment($uri->getTotalSegments());
                        $aksi .= " (ID: {$lastSegment})";
                    }
                }
            }

            // Check if user exists in the database to satisfy the foreign key constraint
            $userExists = false;
            if (!empty($idUser)) {
                $userExists = $db->table('users')->where('ID_USER', $idUser)->countAllResults() > 0;
            }
            
            if (!$userExists) {
                // If it doesn't exist, we must set ID_USER to null due to foreign key constraints,
                // and append the guest/sistem username to AKSI instead!
                $userLabel = $idUser ?? (is_cli() ? 'System/CLI' : 'Guest');
                $aksi .= " (oleh {$userLabel})";
                $idUser = null;
            }
            
            // Insert log to database
            $db->table('audit_logs')->insert([
                'ID_USER'       => $idUser,
                'AKSI'          => $aksi,
                'IP_ADDRESS'    => $ipAddress,
                'TANGGAL_WAKTU' => date('Y-m-d H:i:s'),
            ]);
            
        } catch (\Throwable $e) {
            log_message('error', 'Audit Log Error: ' . $e->getMessage());
        } finally {
            $isLogging = false;
        }
    }
});

