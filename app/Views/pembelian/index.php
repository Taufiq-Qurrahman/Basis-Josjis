<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Manajemen Pembelian Stok Supplier</h2>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Transaksi Logistik Masuk Teh Kota</li></ol>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="<?= base_url('pembelian/tambah'); ?>" class="btn btn-primary">➕ Input Pembelian Baru</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-truck-loading me-1"></i> Riwayat Pasokan Masuk dari Supplier
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Cabang Tujuan</th>
                        <th>Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pembelian)): $no = 1; foreach ($pembelian as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $row['ID_PEMBELIAN']; ?></strong></td>
                            <td><?= date('d-m-Y', strtotime($row['TANGGAL_PEMBELIAN'])); ?></td>
                            <td><?= $row['NAMA_SUPPLIER'] ?? 'Supplier Umum'; ?></td>
                            <td><?= $row['NAMA_CABANG'] ?? 'Pusat'; ?></td>
                            <td>Rp <?= number_format($row['TOTAL_PEMBELIAN'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center text-muted">Belum ada transaksi pembelian bahan baku.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>