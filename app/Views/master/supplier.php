<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Master Supplier</h2>
    <hr>

    <div class="d-flex justify-content-between mb-3">
        <p>Kelola data mitra penyuplai bahan baku mentah beserta area distribusinya</p>
        <button onclick="bukaModalTambah()" class="btn btn-primary">+ Tambah Supplier</button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Supplier</th>
                        <th>Nama Supplier</th>
                        <th>Barang Supply</th>
                        <th>Cabang Distribusi</th>
                        <th>No Telp</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($supplier)): ?>
                        <tr><td colspan="6" class="text-center">Belum ada data supplier.</td></tr>
                    <?php else: ?>
                        <?php foreach($supplier as $s): ?>
                        <tr>
                            <td><strong><?= $s['ID_SUPPLIER'] ?></strong></td>
                            <td><?= $s['NAMA_SUPPLIER'] ?></td>
                            <td><span class="badge bg-info text-dark"><?= $s['BARANG_SUPPLY'] ?? 'Belum Set' ?></span></td>
                            <td><i class="bi bi-geo-alt-fill text-danger"></i> <?= $s['NAMA_CABANG'] ?? 'Semua Cabang / Pusat' ?></td>
                            <td>
                                <?php echo $s['NO_TELP'] ?? $s['NO_TELEPON'] ?? $s['NOMER_TELEPON'] ?? '-'; ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm text-white" 
                                    onclick="bukaModalEdit('<?= $s['ID_SUPPLIER'] ?>', '<?= $s['NAMA_SUPPLIER'] ?>', '<?= $s['BARANG_SUPPLY'] ?? '' ?>', '<?= $s['ID_CABANG'] ?? '' ?>', '<?= $s['NO_TELP'] ?? $s['NO_TELEPON'] ?? $s['NOMER_TELEPON'] ?? '' ?>', '<?= $s['ALAMAT'] ?? '' ?>')">
                                    Edit
                                </button>
                                <a href="<?= base_url('supplier/hapus/'.$s['ID_SUPPLIER']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Hapus supplier ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Supplier Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('supplier/simpan') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Supplier</label>
                        <input type="text" name="id_supplier" class="form-control" placeholder="Contoh: SUP-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" name="nama_supplier" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Menyuplai Barang Apa?</label>
                        <input type="text" name="barang_supply" class="form-control" placeholder="Contoh: Cup Plastik / Gula Pasir / Daun Teh" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Disuplai ke Cabang Mana?</label>
                        <select name="id_cabang" class="form-select" required>
                            <option value="">-- Pilih Cabang Tujuan --</option>
                            <?php foreach($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Kantor (Opsional)</label>
                        <input type="text" name="alamat" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Pengelompokan Supplier</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('supplier/update') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Supplier</label>
                        <input type="text" id="edit_id" name="id_supplier" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier</label>
                        <input type="text" id="edit_nama" name="nama_supplier" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Menyuplai Barang Apa?</label>
                        <input type="text" id="edit_barang" name="barang_supply" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Disuplai ke Cabang Mana?</label>
                        <select id="edit_cabang" name="id_cabang" class="form-select" required>
                            <option value="">-- Pilih Cabang Tujuan --</option>
                            <?php foreach($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" id="edit_telp" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Kantor (Opsional)</label>
                        <input type="text" id="edit_alamat" name="alamat" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalTambah() {
    var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
    myModal.show();
}

function bukaModalEdit(id, nama, barang, cabang, telp, alamat) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_barang').value = barang;
    document.getElementById('edit_cabang').value = cabang;
    document.getElementById('edit_telp').value = telp;
    document.getElementById('edit_alamat').value = alamat;

    var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
    myModal.show();
}
</script>
<?= $this->endSection(); ?>