<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =========================================================================
// 1. AUTHENTICATION & GUEST ACCESS
// =========================================================================
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/proses_login', 'Auth::proses_login');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/logout', 'Auth::logout');


// =========================================================================
// 2. DASHBOARD & REDIRECTS (Akses Umum setelah Login)
// =========================================================================
$routes->get('dashboard', 'Dashboard::index');


// =========================================================================
// 3. MASTER DATA (Hanya untuk role: admin dan manager)
// =========================================================================
$routes->group('', ['filter' => 'role:admin,manager'], static function ($routes) {

    // ---------------------------------------------------------------------
    // Master Cabang
    // ---------------------------------------------------------------------
    $routes->get('cabang', 'MasterCabang::index');
    $routes->get('cabang/tambah', 'MasterCabang::tambah');
    $routes->get('cabang/edit/(:any)', 'MasterCabang::edit/$1');
    $routes->post('cabang/simpanTambah', 'MasterCabang::simpanTambah');
    $routes->post('cabang/simpanEdit/(:any)', 'MasterCabang::simpanEdit/$1');
    $routes->get('cabang/hapus/(:any)', 'MasterCabang::hapus/$1');
    $routes->get('cabang/profil/(:any)', 'MasterCabang::profil/$1');

    // ---------------------------------------------------------------------
    // Master Menu & Harga
    // ---------------------------------------------------------------------
    $routes->get('menu', 'Menu::index');
    $routes->post('menu/simpan', 'Menu::simpan');
    $routes->post('menu/update', 'Menu::update');
    $routes->get('menu/hapus/(:any)', 'Menu::hapus/$1');

    // ---------------------------------------------------------------------
    // Master Karyawan
    // ---------------------------------------------------------------------
    $routes->get('karyawan', 'Karyawan::index');
    $routes->post('karyawan/simpan', 'Karyawan::simpan');
    $routes->post('karyawan/update', 'Karyawan::update');
    $routes->get('karyawan/hapus/(:any)', 'Karyawan::hapus/$1');

    // ---------------------------------------------------------------------
    // Master Supplier
    // ---------------------------------------------------------------------
    $routes->get('supplier', 'Supplier::index');
    $routes->post('supplier/simpan', 'Supplier::simpan');
    $routes->post('supplier/update', 'Supplier::update');
    $routes->get('supplier/hapus/(:any)', 'Supplier::hapus/$1');

    // ---------------------------------------------------------------------
    // Master Pelanggan
    // ---------------------------------------------------------------------
    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->post('pelanggan/simpan', 'Pelanggan::simpan');
    $routes->post('pelanggan/update', 'Pelanggan::update');
    $routes->get('pelanggan/hapus/(:any)', 'Pelanggan::hapus/$1');

    // ---------------------------------------------------------------------
    // Master Mitra Franchisor
    // ---------------------------------------------------------------------
    $routes->get('mitra', 'Mitra::index');
    $routes->get('mitra/profil/(:any)', 'Mitra::profil/$1');
    $routes->post('mitra/simpan', 'Mitra::simpan');
    $routes->post('mitra/update', 'Mitra::update');
    $routes->get('mitra/hapus/(:any)', 'Mitra::hapus/$1');
});


// =========================================================================
// 4. OPERASIONAL & TRANSAKSI
// (Hanya untuk role: admin, manager, dan kasir)
// =========================================================================
$routes->group('', ['filter' => 'role:admin,manager,kasir'], static function ($routes) {

    // ---------------------------------------------------------------------
    // Kasir (Point of Sales)
    // ---------------------------------------------------------------------
    $routes->get('kasir', 'Kasir::index');
    $routes->post('kasir/simpan', 'Kasir::simpan');
    $routes->post('kasir/checkout', 'Kasir::checkout');

    // ---------------------------------------------------------------------
    // Pembelian Stok ke Supplier
    // ---------------------------------------------------------------------
    $routes->get('pembelian', 'Pembelian::index');
    $routes->get('pembelian/tambah', 'Pembelian::tambah');
    $routes->post('pembelian/simpan', 'Pembelian::simpan');

    // ---------------------------------------------------------------------
    // Keluhan / Komplain Pelanggan
    // ---------------------------------------------------------------------
    $routes->get('komplain', 'Komplain::index');

    // ---------------------------------------------------------------------
    // Pengeluaran Harian Cabang
    // ---------------------------------------------------------------------
    $routes->get('pengeluaran', 'Pengeluaran::index');
    $routes->post('pengeluaran/simpan', 'Pengeluaran::simpan');
    $routes->get('pengeluaran/hapus/(:any)', 'Pengeluaran::hapus/$1');
});


// =========================================================================
// 5. MANAJEMEN STOK
// (Bisa diakses oleh admin, manager, dan karyawan)
// =========================================================================
$routes->group('', ['filter' => 'role:admin,manager,karyawan'], static function ($routes) {

    $routes->get('stok', 'Stok::index');
    $routes->post('stok/update_cepat', 'Stok::update_cepat');
});


// =========================================================================
// 6. LAPORAN & OUTPUT DATA
// (Hanya untuk admin, manager, dan owner)
// =========================================================================
$routes->group('', ['filter' => 'role:admin,manager,owner'], static function ($routes) {

    // ---------------------------------------------------------------------
    // Laporan Utama
    // ---------------------------------------------------------------------
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/detail/(:any)', 'Laporan::detail/$1');

    // ---------------------------------------------------------------------
    // Laporan Keuangan & Audit
    // ---------------------------------------------------------------------
    $routes->get('laporan/labarugi', 'Laporan::labaRugi');
    $routes->get('laporan/stokinventori', 'Laporan::stokInventori');
    $routes->get('laporan/stok', 'Laporan::stokInventori');
    $routes->get('laporan/auditlog', 'Laporan::auditLog');
});