<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-4 py-3">

    <?php if (isset($is_form) && $is_form == true): ?>
        <div class="row">
            <div class="col-12">
                <?php 
                    $action_url = base_url('cabang/simpanTambah'); 
                    if (isset($cabang['ID_CABANG']) && !empty($cabang['ID_CABANG'])) {
                        $action_url = base_url('cabang/simpanEdit/' . $cabang['ID_CABANG']);
                    }
                ?>
                <form action="<?= $action_url ?>" method="POST">
                    <h6 class="text-primary mb-3 border-bottom pb-2 font-weight-bold">I. Informasi Data Cabang</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label font-weight-bold">ID Cabang</label>
                            <input type="text" name="ID_CABANG" class="form-control" value="<?= $cabang['ID_CABANG'] ?? '' ?>" <?= !empty($cabang['ID_CABANG']) ? 'readonly' : 'required' ?>>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Nama Cabang</label>
                            <input type="text" name="NAMA_CABANG" class="form-control" value="<?= $cabang['NAMA_CABANG'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kota / Kabupaten</label>
                            <input type="text" name="KOTA" class="form-control" value="<?= $cabang['KOTA'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nomor Telepon Cabang</label>
                            <input type="text" name="NO_TELP" class="form-control" value="<?= $cabang['NO_TELP'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mitra Pemilik</label>
                            <select name="ID_MITRA_PEMILIK" class="form-select" required>
                                <option value="">-- Pilih Mitra Pemilik --</option>
                                <?php if (!empty($mitra)): ?>
                                    <?php foreach ($mitra as $m): ?>
                                        <option value="<?= $m['ID_MITRA'] ?>" <?= (isset($cabang['ID_MITRA_PEMILIK']) && $cabang['ID_MITRA_PEMILIK'] == $m['ID_MITRA']) ? 'selected' : '' ?>>
                                            <?= $m['ID_MITRA'] ?> - <?= $m['NAMA_MITRA'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estimasi Omset Bulanan (Rp)</label>
                            <input type="number" name="AVG_PENJUALAN_BULANAN" class="form-control" value="<?= $cabang['AVG_PENJUALAN_BULANAN'] ?? 0 ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap Cabang</label>
                            <textarea name="ALAMAT" class="form-control" rows="2" required><?= $cabang['ALAMAT'] ?? '' ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h6 class="text-primary mb-0 font-weight-bold">II. Data Staf / Karyawan Cabang</h6>
                        <button type="button" class="btn btn-success btn-sm" id="btn-tambah-karyawan"><i class="fa fa-plus"></i> Tambah Baris Karyawan</button>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle" id="tabel-karyawan">
                            <thead class="table-dark text-center small">
                                <tr>
                                    <th style="width: 12%;">ID Karyawan</th>
                                    <th style="width: 18%;">Nama Lengkap</th>
                                    <th style="width: 15%;">NIK (KTP)</th>
                                    <th style="width: 15%;">Jabatan</th>
                                    <th style="width: 12%;">Gaji Pokok</th>
                                    <th style="width: 13%;">No. Telp Aktif</th>
                                    <th>Alamat Rumah</th>
                                    <th style="width: 7%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($karyawan_existing) && !empty($karyawan_existing)): ?>
                                    <?php foreach ($karyawan_existing as $index => $kar): ?>
                                        <tr>
                                            <td><input type="text" name="karyawan[<?= $index ?>][ID_KARYAWAN]" class="form-control form-control-sm" value="<?= $kar['ID_KARYAWAN'] ?>" readonly></td>
                                            <td><input type="text" name="karyawan[<?= $index ?>][NAMA_KARYAWAN]" class="form-control form-control-sm" value="<?= $kar['NAMA_KARYAWAN'] ?>" required></td>
                                            <td><input type="text" name="karyawan[<?= $index ?>][NIK]" class="form-control form-control-sm" value="<?= $kar['NIK'] ?>" required></td>
                                            <td>
                                                <select name="karyawan[<?= $index ?>][JABATAN]" class="form-select form-select-sm">
                                                    <option value="Manager Outlet" <?= $kar['JABATAN'] == 'Manager Outlet' ? 'selected' : '' ?>>Manager Outlet</option>
                                                    <option value="Supervisor" <?= $kar['JABATAN'] == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                                    <option value="Kasir" <?= $kar['JABATAN'] == 'Kasir' ? 'selected' : '' ?>>Kasir</option>
                                                    <option value="Barista" <?= $kar['JABATAN'] == 'Barista' ? 'selected' : '' ?>>Barista</option>
                                                </select>
                                            </td>
                                            <td><input type="number" name="karyawan[<?= $index ?>][GAJI]" class="form-control form-control-sm" value="<?= $kar['GAJI'] ?>" required></td>
                                            <td><input type="text" name="karyawan[<?= $index ?>][NO_TELEPON]" class="form-control form-control-sm" value="<?= $kar['NO_TELEPON'] ?>" required></td>
                                            <td><input type="text" name="karyawan[<?= $index ?>][ALAMAT_RUMAH]" class="form-control form-control-sm" value="<?= $kar['ALAMAT_RUMAH'] ?>" required></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris">Hapus</button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr class="row-kosong-info">
                                        <td colspan="8" class="text-center text-muted py-3 small">Belum ada data karyawan terikat ke cabang ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('cabang') ?>" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Semua Data</button>
                    </div>
                </form>
            </div>
        </div>

    <?php else: ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-dark"><i class="fas fa-store text-primary me-2"></i>Data Master Jaringan Cabang</h5>
                <a href="<?= base_url('cabang/tambah') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah Cabang
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle w-100">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 10%;">ID Cabang</th>
                                <th>Nama Cabang</th>
                                <th>Kota / Kabupaten</th>
                                <th>No. Telp</th>
                                <th>Mitra Pemilik</th>
                                <th class="text-end">Avg Omset Bulanan</th>
                                <th class="text-center" style="width: 25%;">Aksi & Monitoring</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cabang)): ?>
                                <?php foreach ($cabang as $row): ?>
                                    <tr>
                                        <td class="font-weight-bold text-primary"><?= $row['ID_CABANG'] ?></td>
                                        <td><strong><?= $row['NAMA_CABANG'] ?></strong></td>
                                        <td><?= $row['KOTA'] ?></td>
                                        <td><?= $row['NO_TELP'] ?></td>
                                        <td><span class="badge bg-secondary"><?= $row['ID_MITRA_PEMILIK'] ?></span></td>
                                        <td class="text-end font-weight-bold text-success">
                                            <?= 'Rp ' . number_format($row['AVG_PENJUALAN_BULANAN'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="<?= base_url('cabang/profil/' . $row['ID_CABANG']) ?>" class="btn btn-info btn-sm text-white shadow-sm">
                                                    <i class="fa fa-id-card"></i> Profil
                                                </a>
                                                <a href="<?= base_url('cabang/edit/' . $row['ID_CABANG']) ?>" class="btn btn-warning btn-sm text-white">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="<?= base_url('cabang/hapus/' . $row['ID_CABANG']) ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('Hapus cabang <?= $row['NAMA_CABANG'] ?>?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-muted py-4">Data cabang tidak tersedia.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php if (isset($is_form) && $is_form == true): ?>
<script>
    let barisIndex = <?= isset($karyawan_existing) ? count($karyawan_existing) : 0 ?>;
    document.getElementById('btn-tambah-karyawan').addEventListener('click', function() {
        let rowKosong = document.querySelector('.row-kosong-info');
        if (rowKosong) { rowKosong.remove(); }
        let tbody = document.querySelector('#tabel-karyawan tbody');
        let templateBaris = `
            <tr>
                <td><input type="text" name="karyawan[${barisIndex}][ID_KARYAWAN]" class="form-control form-control-sm" required></td>
                <td><input type="text" name="karyawan[${barisIndex}][NAMA_KARYAWAN]" class="form-control form-control-sm" required></td>
                <td><input type="text" name="karyawan[${barisIndex}][NIK]" class="form-control form-control-sm" required></td>
                <td>
                    <select name="karyawan[${barisIndex}][JABATAN]" class="form-select form-select-sm">
                        <option value="Barista" selected>Barista</option>
                        <option value="Kasir">Kasir</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                </td>
                <td><input type="number" name="karyawan[${barisIndex}][GAJI]" class="form-control form-control-sm" value="2000000" required></td>
                <td><input type="text" name="karyawan[${barisIndex}][NO_TELEPON]" class="form-control form-control-sm" required></td>
                <td><input type="text" name="karyawan[${barisIndex}][ALAMAT_RUMAH]" class="form-control form-control-sm" required></td>
                <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-hapus-baris">Hapus</button></td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', templateBaris);
        barisIndex++;
    });
    document.querySelector('#tabel-karyawan').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-hapus-baris')) { e.target.closest('tr').remove(); }
    });
</script>
<?php endif; ?>

<?= $this->endSection() ?>