<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4"><i class="fas fa-user-tie text-primary me-2"></i>Data Master Mitra Franchisor</h2>
    <hr>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="mb-0 text-muted">Kelola data pemilik modal / mitra investor serta pemantauan relasi sebaran jaringan waralaba Teh Kota</p>
        <button onclick="bukaModalTambah()" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-1"></i> Tambah Mitra</button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="12%">ID Mitra</th>
                            <th>Nama Lengkap</th>
                            <th width="15%">No Telp / WA</th>
                            <th>Alamat Tinggal</th>
                            <th width="15%">Tanggal Gabung</th>
                            <th class="text-center" width="22%">Aksi Operasional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($mitra)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data mitra pemilik modal yang terdaftar.</td></tr>
                        <?php else: ?>
                            <?php foreach($mitra as $m): ?>
                            <tr>
                                <td><span class="badge bg-secondary px-2 py-2 text-monospace"><?= $m['ID_MITRA'] ?></span></td>
                                <td><strong><?= esc($m['NAMA_MITRA']) ?></strong></td>
                                <td><i class="fab fa-whatsapp text-success me-1"></i><?= $m['NO_TELP'] ?? '-' ?></td>
                                <td><?= esc($m['ALAMAT'] ?? '-') ?></td>
                                <td><i class="far fa-calendar-alt text-muted me-1"></i><?= !empty($m['TANGGAL_GABUNG']) ? date('d-m-Y', strtotime($m['TANGGAL_GABUNG'])) : '-' ?></td>
                                <td class="text-center">
                                    <a href="<?= base_url('mitra/profil/'.$m['ID_MITRA']) ?>" class="btn btn-info btn-sm text-white shadow-sm me-1">
                                        <i class="fas fa-id-card"></i> Profil
                                    </a>
                                    
                                    <button type="button" class="btn btn-warning btn-sm text-white shadow-sm me-1" 
                                        onclick="bukaModalEdit('<?= $m['ID_MITRA'] ?>', '<?= addslashes($m['NAMA_MITRA']) ?>', '<?= $m['NO_TELP'] ?? '' ?>', '<?= addslashes($m['ALAMAT'] ?? '') ?>', '<?= $m['TANGGAL_GABUNG'] ?? '' ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    
                                    <a href="<?= base_url('mitra/hapus/'.$m['ID_MITRA']) ?>" 
                                       class="btn btn-danger btn-sm shadow-sm" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus mitra <?= addslashes($m['NAMA_MITRA']) ?>? Semua relasi cabang yang terikat mungkin akan terdampak.')">
                                       <i class="fas fa-trash"></i> Hapus
                                    </a>
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

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Tambah Mitra Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('mitra/simpan') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Mitra</label>
                        <input type="text" name="id_mitra" class="form-control text-monospace" placeholder="Contoh: MIT-01" maxlength="10" required>
                        <small class="text-muted">Maksimal 10 karakter sesuai panjang kolom database.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Mitra</label>
                        <input type="text" name="nama_mitra" class="form-control" maxlength="100" placeholder="Nama Lengkap Mitra" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon / WhatsApp</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 0812345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Rumah (Kota)</label>
                        <textarea name="alamat_tinggal" class="form-control" rows="2" placeholder="Contoh: Surabaya" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" name="tgl_bergabung" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit Identitas Mitra</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('mitra/update') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Mitra (Kunci Utama)</label>
                        <input type="text" id="edit_id" name="id_mitra" class="form-control text-monospace bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Mitra</label>
<input type="text" id="edit_nama" name="nama_mitra" class="form-control" maxlength="100" required>  
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon / WhatsApp</label>
                        <input type="text" id="edit_telp" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Rumah (Kota)</label>
                        <textarea id="edit_alamat" name="alamat_tinggal" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bergabung</label>
                        <input type="date" id="edit_tgl" name="tgl_bergabung" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white"><i class="fas fa-check-circle me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalTambah() {
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
        myModal.show();
    } else {
        $('#modalTambah').modal('show');
    }
}

function bukaModalEdit(id, nama, telp, alamat, tgl) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_telp').value = telp;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_tgl').value = tgl;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        myModal.show();
    } else {
        $('#modalEdit').modal('show');
    }
}
</script>
<?= $this->endSection(); ?>