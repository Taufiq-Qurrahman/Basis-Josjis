<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Audit Log System</h2>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Sistem Informasi Manajemen Teh Kota</li></ol>

    <ul class="nav nav-pills mb-4 bg-light p-2 rounded shadow-sm">
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan'); ?>">📋 Riwayat Transaksi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/labarugi'); ?>">💰 Laba Rugi</a></li>
        <li class="nav-item"><a class="nav-link text-secondary" href="<?= base_url('laporan/stokinventori'); ?>">📦 Stok Inventori</a></li>
        <li class="nav-item"><a class="nav-link active font-weight-bold" href="<?= base_url('laporan/auditlog'); ?>">🔒 Audit Log</a></li>
    </ul>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-danger text-white"><i class="fas fa-shield-alt me-1"></i> Jejak Aktivitas Keamanan Sistem</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" width="100%">
                    <thead class="table-dark">
                        <tr>
                            <?php if (!empty($logs)): ?>
                                <th>No</th>
                                <?php foreach (array_keys($logs[0]) as $columnName): ?>
                                    <th><?= strtoupper(str_replace('_', ' ', $columnName)); ?></th>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <th>Log Informasi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): $no = 1; foreach ($logs as $row): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <?php foreach ($row as $value): ?>
                                    <td><?= esc($value); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="10" class="text-center text-muted">Belum ada rekaman aktivitas (Tabel audit_logs kosong).</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>