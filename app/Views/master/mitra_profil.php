<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mt-4"><i class="fas fa-id-card text-primary me-2"></i>Profil Mitra Franchisor</h2>
            <p class="mb-0 text-muted">Detail mitra dan cabang terkait yang terhubung dengan jaringan Teh Kota.</p>
        </div>
        <a href="<?= base_url('mitra') ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Mitra
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-bottom-0">
                    <strong>Informasi Mitra</strong>
                </div>
                <div class="card-body">
                    <p class="mb-2"><span class="text-muted">ID Mitra</span><br><strong><?= esc($mitra['ID_MITRA']) ?></strong></p>
                    <p class="mb-2"><span class="text-muted">Nama Mitra</span><br><strong><?= esc($mitra['NAMA_MITRA']) ?></strong></p>
                    <p class="mb-2"><span class="text-muted">Telepon / WhatsApp</span><br><?= esc($mitra['NO_TELP'] ?? '-') ?></p>
                    <p class="mb-2"><span class="text-muted">Alamat</span><br><?= esc($mitra['ALAMAT'] ?? '-') ?></p>
                    <p class="mb-0"><span class="text-muted">Tanggal Bergabung</span><br><?= !empty($mitra['TANGGAL_GABUNG']) ? date('d-m-Y', strtotime($mitra['TANGGAL_GABUNG'])) : '-' ?></p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0">
                    <strong>Statistik</strong>
                </div>
                <div class="card-body">
                    <p class="mb-2"><span class="text-muted">Total Cabang Terhubung</span><br><strong><?= count($cabangs) ?></strong></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                    <strong>Daftar Cabang Terafiliasi</strong>
                    <span class="badge bg-primary rounded-pill"><?= count($cabangs) ?> Cabang</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($cabangs)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-building fa-2x mb-3"></i>
                            <p class="mb-0">Belum ada cabang yang terhubung dengan mitra ini.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Cabang</th>
                                        <th>Nama Cabang</th>
                                        <th>Alamat</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cabangs as $cabang): ?>
                                        <tr>
                                            <td><?= esc($cabang['ID_CABANG']) ?></td>
                                            <td><?= esc($cabang['NAMA_CABANG']) ?></td>
                                            <td><?= esc($cabang['ALAMAT']) ?></td>
                                            <td class="text-center">
                                                <a href="<?= base_url('cabang/profil/' . $cabang['ID_CABANG']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
