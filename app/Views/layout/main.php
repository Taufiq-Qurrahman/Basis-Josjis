<?php
// PENGAMAN OTOMATIS: Jika ada controller yang lupa mengirim data cabang,
// sistem akan otomatis menariknya langsung dari database agar halaman tidak crash.
if (!isset($cabang)) {
    $db = \Config\Database::connect();
    $cabang = $db->table('cabang')->get()->getResultArray();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Teh Kota | Dashboard'; ?></title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= base_url('dashboard'); ?>" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto ml-auto">
      <li class="nav-item">
        <a class="nav-link text-danger font-weight-bold" href="<?= base_url('logout'); ?>">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('dashboard'); ?>" class="brand-link">
      <span class="brand-text font-weight-light pl-3">🥤 <b>Teh Kota SIM</b></span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block pl-2">User Role: <span class="badge badge-success text-capitalize"><?= session()->get('role') ?? 'Pegawai'; ?></span></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-header">UTAMA</li>
          <li class="nav-item">
            <a href="<?= base_url('dashboard'); ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <?php if (in_array(session()->get('role'), ['admin', 'manager'])): ?>
            <li class="nav-header">DATA INDUK</li>
            <li class="nav-item">
              <a href="<?= base_url('cabang'); ?>" class="nav-link">
                <i class="nav-icon fas fa-store"></i>
                <p>Master Cabang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('mitra'); ?>" class="nav-link">
                <i class="nav-icon fas fa-handshake"></i>
                <p>Master Mitra Franchisor</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('menu'); ?>" class="nav-link">
                <i class="nav-icon fas fa-utensils"></i>
                <p>Master Menu & Harga</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('karyawan'); ?>" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>Master Karyawan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('supplier'); ?>" class="nav-link">
                <i class="nav-icon fas fa-truck"></i>
                <p>Master Supplier</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('pelanggan'); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-friends"></i>
                <p>Master Pelanggan</p>
              </a>
            </li>
          <?php endif; ?>

          <li class="nav-header">TRANSAKSI & OPERASIONAL</li>
          <li class="nav-item">
            <a href="<?= base_url('kasir'); ?>" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>Kasir (POS)</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('pembelian'); ?>" class="nav-link">
              <i class="nav-icon fas fa-truck-loading"></i>
              <p>Pembelian Supplier</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('pengeluaran'); ?>" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>Biaya Operasional</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('stok'); ?>" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Manajemen Stok</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('komplain'); ?>" class="nav-link">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Keluhan / Komplain</p>
            </a>
          </li>

          <li class="nav-header">OUTPUT ANALISIS</li>
          <li class="nav-item">
            <a href="<?= base_url('laporan'); ?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Laporan Laba Rugi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('laporan/stok'); ?>" class="nav-link">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Laporan Stok Cabang</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('laporan/auditlog'); ?>" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>Audit Log System</p>
            </a>
          </li>

        </ul>
      </nav>
      </div>
    </aside>

  <div class="content-wrapper">
    <section class="content pt-4">
      <div class="container-fluid">
        <?= $this->renderSection('content'); ?>
      </div></section>
    </div>
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Kelompok 7
    </div>
    <strong>Copyright &copy; 2026 <a href="#">SIM Teh Kota</a>.</strong> All rights reserved.
  </footer>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>