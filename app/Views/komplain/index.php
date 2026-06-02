<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">
    <div class="mb-4">
        <h2 class="mt-4"><i class="fas fa-exclamation-triangle text-warning"></i> Keluhan / Komplain</h2>
        <p class="text-muted">Halaman ini digunakan untuk menampilkan keluhan atau komplain pelanggan terkait cabang dan layanan Teh Kota.</p>
    </div>
    <hr>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <!-- Tampilan Persis Sesuai Request Mockup -->
            <div class="p-3 mb-3 text-white rounded" style="background-color: #0d9488; font-size: 0.95rem; font-weight: 500;">
                Info: Fitur komplain sudah tersedia. Tambahkan logika dan tampilan komplain pelanggan sesuai kebutuhan aplikasi Anda.
            </div>
            <p class="text-muted mb-4" style="font-size: 0.95rem;">
                Sistem keluhan dan komplain pelanggan kini telah aktif sepenuhnya dan terhubung langsung ke database. Kelola kritik, keluhan, masukan, dan status penanganan operasional cabang di bawah ini.
            </p>

            <hr class="my-4">

            <!-- Data Table Keluhan -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="m-0 font-weight-bold text-dark"><i class="fas fa-list me-1"></i> Data Keluhan & Komplain Pelanggan</h5>
                <button type="button" class="btn btn-success btn-sm px-3" data-toggle="modal" data-bs-toggle="modal" data-target="#modalTambahKomplain" data-bs-target="#modalTambahKomplain">
                    <i class="fa fa-plus-circle me-1"></i> Input Komplain Baru
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle tabel-sim-tehkota w-100">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 15%;">Pelanggan</th>
                            <th style="width: 15%;">Cabang</th>
                            <th>Isi Komplain / Keluhan</th>
                            <th class="text-center" style="width: 12%;">Status</th>
                            <th class="text-center" style="width: 18%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($komplain)): $no = 1; ?>
                            <?php foreach ($komplain as $row): 
                                $id       = $row['ID_KOMPLAIN'] ?? '';
                                $idPbl    = $row['ID_PEMBELI'] ?? '';
                                $idCbg    = $row['ID_CABANG'] ?? '';
                                $pembeliName  = $row['NAMA_PEMBELI'] ?? 'Umum';
                                $cabangName   = $row['NAMA_CABANG'] ?? 'Semua';
                                $isi      = $row['ISI_KOMPLAIN'] ?? '';
                                $tanggal  = $row['TANGGAL_KOMPLAIN'] ?? '';
                                $status   = $row['STATUS'] ?? 'Pending';

                                // Color coding status badge
                                $badgeClass = 'bg-secondary';
                                if ($status === 'Pending') {
                                    $badgeClass = 'bg-danger';
                                } elseif ($status === 'Diproses') {
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif ($status === 'Selesai') {
                                    $badgeClass = 'bg-success';
                                }
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($tanggal)) ?> WIB</td>
                                    <td class="font-weight-bold text-primary"><?= $pembeliName ?></td>
                                    <td><?= $cabangName ?></td>
                                    <td><?= esc($isi) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $badgeClass ?> px-3 py-2">
                                            <?= $status ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-warning btn-sm text-white btn-edit-komplain" 
                                                data-toggle="modal" 
                                                data-bs-toggle="modal" 
                                                data-target="#modalEditKomplain"
                                                data-bs-target="#modalEditKomplain"
                                                data-id="<?= $id ?>" 
                                                data-id-pembeli="<?= $idPbl ?>" 
                                                data-id-cabang="<?= $idCbg ?>" 
                                                data-isi="<?= esc($isi) ?>"
                                                data-status="<?= $status ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            
                                            <a href="<?= base_url('komplain/hapus/' . $id) ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('Apakah Anda yakin ingin menghapus data komplain ini?');">
                                                <i class="fa fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Belum ada data keluhan atau komplain yang masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Komplain -->
<div class="modal fade" id="modalTambahKomplain" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Input Komplain Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('komplain/simpan') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Pelanggan / Pembeli</label>
                        <select name="id_pembeli" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($pembeli as $p): ?>
                                <option value="<?= $p['ID_PEMBELI'] ?>"><?= $p['NAMA_PEMBELI'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Cabang yang Dikomplain</label>
                        <select name="id_cabang" class="form-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            <?php foreach ($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Isi Keluhan / Komplain</label>
                        <textarea name="isi_komplain" class="form-control" rows="4" placeholder="Detail keluhan pelanggan..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Status Awal</label>
                        <select name="status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Komplain</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Komplain -->
<div class="modal fade" id="modalEditKomplain" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Komplain</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('komplain/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" id="edit_id" name="id_komplain">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Pelanggan / Pembeli</label>
                        <select id="edit_pembeli" name="id_pembeli" class="form-select" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php foreach ($pembeli as $p): ?>
                                <option value="<?= $p['ID_PEMBELI'] ?>"><?= $p['NAMA_PEMBELI'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Cabang yang Dikomplain</label>
                        <select id="edit_cabang" name="id_cabang" class="form-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            <?php foreach ($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Isi Keluhan / Komplain</label>
                        <textarea id="edit_isi" name="isi_komplain" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Perbarui Komplain</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-edit-komplain');
            if (btn) {
                document.getElementById('edit_id').value = btn.getAttribute('data-id');
                document.getElementById('edit_pembeli').value = btn.getAttribute('data-id-pembeli');
                document.getElementById('edit_cabang').value = btn.getAttribute('data-id-cabang');
                document.getElementById('edit_isi').value = btn.getAttribute('data-isi');
                document.getElementById('edit_status').value = btn.getAttribute('data-status');
            }
        });
    });
</script>

<?= $this->endSection() ?>
