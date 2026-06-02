<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Laporan Laba Rugi</h2>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Sistem Informasi Manajemen Teh Kota</li></ol>

    <ul class="nav nav-pills mb-4 bg-light p-2 rounded shadow-sm">
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan'); ?>">📋 Riwayat Transaksi</a></li>
        <li class="nav-item"><a class="nav-link active font-weight-bold" href="<?= base_url('laporan/labarugi'); ?>">💰 Laba Rugi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/stokinventori'); ?>">📦 Stok Inventori</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/auditlog'); ?>">🔒 Audit Log</a></li>
    </ul>

    <div class="row mb-4">
        <div class="col-md-6">
            <form action="/laporan/labarugi" method="get" class="row g-2">
                <div class="col-auto"><input type="month" name="bulan" class="form-control" value="<?= $bulan; ?>"></div>
                <div class="col-auto"><button type="submit" class="btn btn-primary px-4">Filter Bulan</button></div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white shadow"><div class="card-body"><h5>Total Pendapatan (Penjualan)</h5><h3>Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></h3></div></div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-danger text-white shadow"><div class="card-body"><h5>Total Pengeluaran Operasional</h5><h3>Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></h3></div></div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card <?= ($laba_rugi >= 0) ? 'bg-success' : 'bg-warning text-dark'; ?> text-white shadow">
                <div class="card-body"><h5>Kondisi Bersih (Laba/Rugi)</h5><h3>Rp <?= number_format($laba_rugi, 0, ',', '.'); ?></h3></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>