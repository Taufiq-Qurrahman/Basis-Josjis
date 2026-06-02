<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">
    <h2 class="mt-4">Data Master Pelanggan (Pembeli)</h2>
    <hr>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="mb-3 d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#modalTambahPelanggan" data-bs-target="#modalTambahPelanggan">
            <i class="fa fa-plus"></i> Tambah Pelanggan
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle tabel-sim-tehkota w-100">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%;">ID Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>No. Telepon</th>
                            <th class="text-center" style="width: 15%;">Point / Loyalitas</th>
                            <th class="text-center" style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pelanggan)): ?>
                            <?php foreach ($pelanggan as $row): 
                                $id    = $row['ID_PEMBELI'] ?? '';
                                $nama  = $row['NAMA_PEMBELI'] ?? '';
                                $telp  = $row['NOMER_TELEPON_PEMBELI'] ?? '-';
                                $point = $row['POINT'] ?? 0;
                            ?>
                                <tr>
                                    <td class="font-weight-bold text-primary"><?= $id ?></td>
                                    <td><?= $nama ?></td>
                                    <td><?= $telp ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success px-3 py-2 fs-6">
                                            <i class="fa fa-star me-1 text-warning"></i> <?= $point ?> Pts
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-warning btn-sm text-white btn-edit-pelanggan" 
                                                data-toggle="modal" 
                                                data-bs-toggle="modal" 
                                                data-target="#modalEditPelanggan"
                                                data-bs-target="#modalEditPelanggan"
                                                data-id="<?= $id ?>" 
                                                data-nama="<?= $nama ?>" 
                                                data-telp="<?= $telp ?>" 
                                                data-point="<?= $point ?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            
                                            <a href="<?= base_url('pelanggan/hapus/' . $id) ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= $nama ?>?');">
                                                <i class="fa fa-trash"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('pelanggan/simpan') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">ID Pelanggan / Pembeli</label>
                        <input type="text" name="ID_PEMBELI" class="form-control" placeholder="Contoh: CUST002" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="NAMA_PEMBELI" class="form-control" placeholder="Masukkan nama pelanggan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon / WhatsApp</label>
                        <input type="text" name="NOMER_TELEPON_PEMBELI" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Point Awal</label>
                        <input type="number" name="POINT" class="form-control" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Data Pelanggan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('pelanggan/update') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">ID Pelanggan / Pembeli</label>
                        <input type="text" id="edit_id" name="ID_PEMBELI" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="edit_nama" name="NAMA_PEMBELI" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon / WhatsApp</label>
                        <input type="text" id="edit_telp" name="NOMER_TELEPON_PEMBELI" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Point / Loyalitas</label>
                        <input type="number" id="edit_point" name="POINT" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Menggunakan Vanilla JS yang kebal dari error urutan loading jQuery
    document.addEventListener("DOMContentLoaded", function() {
        document.body.addEventListener('click', function(e) {
            // Cek apakah elemen yang diklik atau elemen parentnya memiliki class btn-edit-pelanggan
            const btn = e.target.closest('.btn-edit-pelanggan');
            
            if (btn) {
                // Tarik data atribut dan lempar ke input form di dalam modal
                document.getElementById('edit_id').value = btn.getAttribute('data-id');
                document.getElementById('edit_nama').value = btn.getAttribute('data-nama');
                document.getElementById('edit_telp').value = btn.getAttribute('data-telp');
                document.getElementById('edit_point').value = btn.getAttribute('data-point');
            }
        });
    });
</script>

<?= $this->endSection() ?>