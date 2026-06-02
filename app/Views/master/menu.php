<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Master Menu & Harga</h2>
    <hr>

    <div class="d-flex justify-content-between mb-3">
        <p>Kelola daftar varian menu, stok bahan baku, beserta harga beli dan harga jual Teh Kota</p>
        <button onclick="bukaModalTambah()" class="btn btn-primary">+ Tambah Menu</button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Menu</th>
                        <th>Nama Menu</th>
                        <th>Definisi Menu</th>
                        <th>Stok</th>
                        <th>Harga Beli (Modal)</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($menu)): ?>
                        <tr><td colspan="7" class="text-center">Belum ada data menu.</td></tr>
                    <?php else: ?>
                        <?php foreach($menu as $m): ?>
                        <tr>
                            <td><strong><?= $m['ID_MENU'] ?></strong></td>
                            <td><?= $m['NAMA_MENU'] ?></td>
                            <td><?= $m['DEFINISI_MENU'] ?? '-' ?></td>
                            <td><?= $m['STOK'] ?? 0 ?> pcs</td>
                            <td>Rp <?= number_format($m['HARGA_BELI'] ?? 0, 0, ',', '.') ?></td>
                            <td>
                                <?php 
                                    $harga_jual = $m['HARGA_MENU'] ?? $m['HARGA'] ?? $m['HARGA_JUAL'] ?? 0;
                                    echo "Rp " . number_format($harga_jual, 0, ',', '.');
                                ?>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning btn-sm text-white" 
                                    onclick="bukaModalEdit('<?= $m['ID_MENU'] ?>', '<?= $m['NAMA_MENU'] ?>', '<?= $m['DEFINISI_MENU'] ?? '' ?>', '<?= $m['STOK'] ?? 0 ?>', '<?= $m['HARGA_BELI'] ?? 0 ?>', '<?= $harga_jual ?>')">
                                    Edit
                                </button>
                                <a href="<?= base_url('menu/hapus/'.$m['ID_MENU']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
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
                <h5 class="modal-title">Tambah Menu Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/simpan') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Menu</label>
                        <input type="text" name="id_menu" class="form-control" placeholder="Contoh: MN-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Definisi Menu</label>
                        <textarea name="definisi_menu" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Beli (Modal)</label>
                        <input type="number" name="harga_beli" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_menu" class="form-control" value="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Menu & Harga</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('menu/update') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Menu</label>
                        <input type="text" id="edit_id" name="id_menu" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" id="edit_nama" name="nama_menu" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Definisi Menu</label>
                        <textarea id="edit_definisi" name="definisi_menu" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" id="edit_stok" name="stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Beli (Modal)</label>
                        <input type="number" id="edit_beli" name="harga_beli" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" id="edit_jual" name="harga_menu" class="form-control" required>
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
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
        myModal.show();
    } else {
        $('#modalTambah').modal('show');
    }
}

function bukaModalEdit(id, nama, definisi, stok, beli, jual) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_definisi').value = definisi;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_beli').value = beli;
    document.getElementById('edit_jual').value = jual;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        myModal.show();
    } else {
        $('#modalEdit').modal('show');
    }
}
</script>
<?= $this->endSection(); ?>