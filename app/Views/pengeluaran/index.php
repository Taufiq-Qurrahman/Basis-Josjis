<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="content-header p-0 mb-3">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark font-weight-bold">Pengeluaran Operasional Cabang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard'); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Biaya Operasional</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <h5><i class="icon fas fa-check"></i> Sukses!</h5>
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-1 text-primary"></i> Catat Biaya Baru</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('pengeluaran/simpan'); ?>" method="post">
                    <?= csrf_field(); ?>
                    
                    <div class="form-group">
                        <label class="font-weight-bold text-secondary">Pilih Unit Cabang</label>
                        <select name="id_cabang" class="form-control custom-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            <?php foreach($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG']; ?>"><?= $c['NAMA_CABANG']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-secondary">Nominal Pengeluaran (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Rp</span>
                            </div>
                            <input type="number" name="nominal" class="form-control" placeholder="Contoh: 50000" min="1000" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-secondary">Keterangan Operasional</label>
                        <textarea name="keterangan" class="form-control" rows="4" placeholder="Contoh: Beli es batu kristal 2 kantong / Bayar listrik outlet" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm mt-4">
                        <i class="fas fa-save mr-1"></i> Simpan Pengeluaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-dark shadow-sm">
            <div class="card-header bg-dark">
                <h3 class="card-title font-weight-bold"><i class="fas fa-receipt mr-1"></i> Log Histori Biaya Operasional</h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="pl-3" width="5%">No</th>
                            <th width="20%">Tanggal</th>
                            <th width="25%">Cabang</th>
                            <th>Keterangan</th>
                            <th width="20%">Nominal</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pengeluaran)): $no = 1; foreach ($pengeluaran as $row): ?>
                            <tr>
                                <td class="pl-3 text-muted"><?= $no++; ?></td>
                                <td>
                                    <span class="d-block font-weight-bold small"><?= date('d M Y', strtotime($row['TANGGAL_PENGELUARAN'])); ?></span>
                                    <small class="text-muted"><?= date('H:i', strtotime($row['TANGGAL_PENGELUARAN'])); ?> WIB</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border pl-2 pr-2 pt-1 pb-1">
                                        <i class="fas fa-store text-primary mr-1"></i> <?= $row['NAMA_CABANG'] ?? 'Pusat / Umum'; ?>
                                    </span>
                                </td>
                                <td class="text-wrap"><?= esc($row['KETERANGAN']); ?></td>
                                <td class="text-danger font-weight-bold">
                                    Rp <?= number_format($row['NOMINAL'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('pengeluaran/hapus/' . $row['ID_PENGELUARAN']); ?>" 
                                       class="btn btn-sm text-danger" 
                                       onclick="return confirm('Hapus catatan pengeluaran harian ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fa-2x d-block mb-2 text-muted opacity-50"></i>
                                    Belum ada catatan biaya operasional yang diinput.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>