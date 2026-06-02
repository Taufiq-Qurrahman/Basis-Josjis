<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Data Master Karyawan</h2>
    <hr>

    <div class="d-flex justify-content-between mb-3">
        <p>Kelola berkas arsip, jabatan, dan lokasi penempatan tugas staf Teh Kota</p>
        <button onclick="bukaModalTambah()" class="btn btn-primary">+ Tambah Karyawan</button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>No Telp</th>
                        <th>Penempatan Tugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($karyawan)): ?>
                        <tr><td colspan="6" class="text-center">Belum ada data karyawan.</td></tr>
                    <?php else: ?>
                        <?php foreach($karyawan as $k): ?>
                        <tr>
                            <td><strong><?= $k['ID_KARYAWAN'] ?></strong></td>
                            <td><?= $k['NAMA_KARYAWAN'] ?></td>
                            <td><span class="badge bg-secondary"><?= $k['JABATAN'] ?></span></td>
                            
                            <td><?= $k['NO_TELEPON'] ?? '-' ?></td>
                            
                            <td>
                                <i class="bi bi-geo-alt-fill text-danger"></i> 
                                <strong><?= $k['NAMA_CABANG'] ?? 'Belum Ditempatkan' ?></strong>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm text-white"
                                    onclick="bukaModalDetail('<?= $k['ID_KARYAWAN'] ?>', '<?= $k['NAMA_KARYAWAN'] ?>', '<?= $k['NIK'] ?? '-' ?>', '<?= $k['JABATAN'] ?>', '<?= $k['NO_TELEPON'] ?? '-' ?>', '<?= $k['ALAMAT_RUMAH'] ?? '-' ?>', 'Rp ' + '<?= number_format($k['GAJI'] ?? 0, 0, ',', '.') ?>', '<?= $k['STATUS_KONTRAK'] ?? 'Karyawan Tetap' ?>', '<?= $k['NAMA_CABANG'] ?? 'Pusat' ?>')">
                                    🔍 Profil
                                </button>
                                
                                <button type="button" class="btn btn-warning btn-sm text-white" 
                                    onclick="bukaModalEdit('<?= $k['ID_KARYAWAN'] ?>', '<?= $k['NAMA_KARYAWAN'] ?>', '<?= $k['NIK'] ?? '' ?>', '<?= $k['JABATAN'] ?>', '<?= $k['NO_TELEPON'] ?? '' ?>', '<?= $k['ALAMAT_RUMAH'] ?? '' ?>', '<?= $k['GAJI'] ?? 0 ?>', '<?= $k['STATUS_KONTRAK'] ?? '' ?>', '<?= $k['ID_CABANG'] ?? '' ?>')">
                                    Edit
                                </button>
                                <a href="<?= base_url('karyawan/hapus/'.$k['ID_KARYAWAN']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data karyawan ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">📄 Dokumen Arsip Karyawan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped mb-0">
                    <tr><td class="fw-bold ps-3" style="width: 40%;">ID Karyawan</td><td id="det_id"></td></tr>
                    <tr><td class="fw-bold ps-3">Nama Lengkap</td><td id="det_nama" class="fw-bold text-primary"></td></tr>
                    <tr><td class="fw-bold ps-3">No NIK (KTP)</td><td id="det_nik"></td></tr>
                    <tr><td class="fw-bold ps-3">Jabatan</td><td><span class="badge bg-secondary" id="det_jabatan"></span></td></tr>
                    <tr><td class="fw-bold ps-3">No HP / WA</td><td id="det_telp"></td></tr>
                    <tr><td class="fw-bold ps-3">Alamat Rumah</td><td id="det_alamat"></td></tr>
                    <tr><td class="fw-bold ps-3">Gaji Pokok</td><td id="det_gaji" class="text-success fw-bold"></td></tr>
                    <tr><td class="fw-bold ps-3">Status Kerja</td><td id="det_kontrak"></td></tr>
                    <tr><td class="fw-bold ps-3">Lokasi Tugas</td><td id="det_cabang" class="fw-bold"></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Karyawan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('karyawan/simpan') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID Karyawan</label>
                            <input type="text" name="id_karyawan" class="form-control" placeholder="Contoh: EMP-HO02" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor NIK KTP</label>
                            <input type="text" name="nik" class="form-control" placeholder="16 Digit KTP" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_karyawan" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Supervisor Area Jateng">Supervisor Area Jateng</option>
                                <option value="Supervisor Area Jatim">Supervisor Area Jatim</option>
                                <option value="Manager">Manager</option>
                                <option value="Barista">Barista</option>
                                <option value="Kasir">Kasir</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" name="nomer_telepon" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Rumah Sesuai KTP</label>
                        <textarea name="alamat_rumah" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Bulanan (Rp)</label>
                            <input type="number" name="gaji" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Kontrak</label>
                            <select name="status_kontrak" class="form-select" required>
                                <option value="Karyawan Tetap">Karyawan Tetap</option>
                                <option value="Kontrak (6 Bulan)">Kontrak (6 Bulan)</option>
                                <option value="Kontrak (1 Tahun)">Kontrak (1 Tahun)</option>
                                <option value="Masa Training">Masa Training</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Penempatan Kerja</label>
                        <select name="id_cabang" class="form-select" required>
                            <option value="">-- Pilih Cabang Tugas --</option>
                            <?php foreach($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Data Karyawan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('karyawan/update') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id_karyawan">
                    <div class="mb-3">
                        <label class="form-label">Nomor NIK KTP</label>
                        <input type="text" id="edit_nik" name="nik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" id="edit_nama" name="nama_karyawan" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <select id="edit_jabatan" name="jabatan" class="form-select" required>
                                <option value="Direktur Operasional">Direktur Operasional</option>
                                <option value="Supervisor Area Jateng">Supervisor Area Jateng</option>
                                <option value="Supervisor Area Jatim">Supervisor Area Jatim</option>
                                <option value="Manager">Manager</option>
                                <option value="Barista">Barista</option>
                                <option value="Kasir">Kasir</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No Telepon</label>
                            <input type="text" id="edit_telp" name="nomer_telepon" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Rumah Sesuai KTP</label>
                        <textarea id="edit_alamat" name="alamat_rumah" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Bulanan (Rp)</label>
                            <input type="number" id="edit_gaji" name="gaji" class="form-control" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Kontrak</label>
                            <select id="edit_kontrak" name="status_kontrak" class="form-select" required>
                                <option value="Karyawan Tetap">Karyawan Tetap</option>
                                <option value="Kontrak (6 Bulan)">Kontrak (6 Bulan)</option>
                                <option value="Kontrak (1 Tahun)">Kontrak (1 Tahun)</option>
                                <option value="Masa Training">Masa Training</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Penempatan Kerja</label>
                        <select id="edit_cabang" name="id_cabang" class="form-select" required>
                            <?php foreach($cabang as $c): ?>
                                <option value="<?= $c['ID_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                            <?php endforeach; ?>
                        </select>
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

function bukaModalDetail(id, nama, nik, jabatan, telp, alamat, gaji, kontrak, cabang) {
    document.getElementById('det_id').innerText = id;
    document.getElementById('det_nama').innerText = nama;
    document.getElementById('det_nik').innerText = nik;
    document.getElementById('det_jabatan').innerText = jabatan;
    document.getElementById('det_telp').innerText = telp;
    document.getElementById('det_alamat').innerText = alamat;
    document.getElementById('det_gaji').innerText = gaji;
    document.getElementById('det_kontrak').innerText = kontrak;
    document.getElementById('det_cabang').innerText = cabang;

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalDetail'));
        myModal.show();
    } else {
        $('#modalDetail').modal('show');
    }
}

function bukaModalEdit(id, nama, nik, jabatan, telp, alamat, gaji, kontrak, cabang) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nik').value = nik;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_jabatan').value = jabatan;
    document.getElementById('edit_telp').value = telp;
    document.getElementById('edit_alamat').value = alamat;
    document.getElementById('edit_gaji').value = gaji;
    document.getElementById('edit_kontrak').value = kontrak;
    document.getElementById('edit_cabang').value = cabang; 

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('modalEdit'));
        myModal.show();
    } else {
        $('#modalEdit').modal('show');
    }
}
</script>
<?= $this->endSection(); ?>