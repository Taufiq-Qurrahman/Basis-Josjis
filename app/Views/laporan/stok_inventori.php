<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Laporan Stok Inventori Cabang</h2>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Sistem Informasi Manajemen Teh Kota</li></ol>

    <ul class="nav nav-pills mb-4 bg-light p-2 rounded shadow-sm">
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan'); ?>">📋 Riwayat Transaksi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/labarugi'); ?>">💰 Laba Rugi</a></li>
        <li class="nav-item"><a class="nav-link active font-weight-bold" href="<?= base_url('laporan/stokinventori'); ?>">📦 Stok Inventori</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/auditlog'); ?>">🔒 Audit Log</a></li>
    </ul>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white"><i class="fas fa-boxes me-1"></i> Status Kuantitas Stok Bahan Baku Outlet</div>
        <div class="card-body">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="table-dark">
                    <tr><th>No</th><th>Nama Cabang</th><th>Nama Item / Bahan Baku</th><th>Sisa Stok</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($stok)): $no = 1; foreach ($stok as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['NAMA_CABANG'] ?? 'Pusat'; ?></td>
                            <td><?= $row['NAMA_MENU']; ?></td>
                            <td><span class="badge bg-success"><?= $row['JUMLAH_STOK'] ?? 0; ?> Pcs</span></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center text-muted">Data stok tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>