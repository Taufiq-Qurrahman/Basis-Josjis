<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Cabang | SIM Teh Kota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; min-height: 100vh; }
        .sidebar { min-height: 100vh; background: #1a1a1a; color: white; padding-top: 20px; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 12px 20px; transition: all 0.2s; }
        .sidebar a:hover { background: #2a2a2a; color: white; padding-left: 25px; }
        .sidebar .active { background: #222222; color: #ffc107; font-weight: bold; border-left: 4px solid #ffc107; }
        .nav-header { font-size: 0.75rem; text-transform: uppercase; padding: 15px 20px 5px; color: #6c757d; font-weight: bold; letter-spacing: 1px; }
        .header-profile { background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%); color: white; border-radius: 15px; }
        .text-gold { color: #ffc107; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .card-header { background-color: #fff; border-bottom: 1px solid #f2f2f2; font-weight: bold; padding: 15px 20px; border-radius: 12px 12px 0 0 !important; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar d-none d-md-block p-0">
            <h4 class="text-center mb-4 py-2 font-weight-bold" style="color: #ffc107; letter-spacing: 1px;">Teh Kota</h4>
            <a href="<?= base_url('dashboard') ?>"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
            <div class="nav-header">DATA MASTER</div>
            <a class="active" href="<?= base_url('cabang') ?>"><i class="fa-solid fa-building me-2"></i> Cabang</a>
            <a href="<?= base_url('menu') ?>"><i class="fa-solid fa-mug-hot me-2"></i> Menu & Harga</a>
            <a href="<?= base_url('karyawan') ?>"><i class="fa-solid fa-users me-2"></i> Karyawan</a>
            <a href="<?= base_url('supplier') ?>"><i class="fa-solid fa-box me-2"></i> Supplier</a>
            <a href="<?= base_url('mitra') ?>"><i class="fa-solid fa-handshake me-2"></i> Mitra Franchisor</a>
            <a href="<?= base_url('pelanggan') ?>"><i class="fa-solid fa-bag-shopping me-2"></i> Pelanggan</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?= base_url('cabang') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Cabang
                </a>
                <span class="text-muted small bg-white shadow-sm px-3 py-2 rounded-pill">
                    ID Cabang: <strong class="text-dark"><?= $cabang['ID_CABANG'] ?></strong>
                </span>
            </div>

            <div class="header-profile p-4 mb-4 shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <span class="badge bg-warning text-dark mb-2 font-weight-bold">OUTLET RESMI</span>
                        <h2 class="font-weight-bold mb-1 text-gold"><?= esc($cabang['NAMA_CABANG']) ?></h2>
                        <p class="mb-0 text-light opacity-75"><i class="fa-solid fa-map-marker-alt text-danger me-2"></i><?= esc($cabang['ALAMAT']) ?>, Kota <?= esc($cabang['KOTA']) ?></p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="bg-white bg-opacity-10 p-3 rounded-3 border border-white border-opacity-10">
                            <small class="d-block text-white-50">Estimasi Omset Bulanan</small>
                            <h3 class="mb-0 text-warning font-weight-bold">Rp <?= number_format($cabang['AVG_PENJUALAN_BULANAN'] ?? 0, 0, ',', '.') ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header"><i class="fa-solid fa-circle-info text-primary me-2"></i>Informasi Manajemen</div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted ps-3 py-3">Mitra Pemilik</td>
                                        <td class="font-weight-bold text-end pe-3 py-3"><?= esc($cabang['NAMA_MITRA'] ?? 'Internal / Pusat') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-3 py-3">Telepon Outlet</td>
                                        <td class="text-end pe-3 py-3"><?= esc($cabang['NO_TELP'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted ps-3 py-3">Telepon Pemilik</td>
                                        <td class="text-end pe-3 py-3"><?= esc($cabang['TELP_MITRA'] ?? '-') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i>Log 7 Hari Terakhir</div>
                        <div class="card-body p-0">
                            <?php if(empty($penjualan_harian)): ?>
                                <p class="text-muted text-center py-4 my-0">Belum ada aktivitas penjualan tercatat.</p>
                            <?php else: ?>
                                <table class="table mb-0 table-striped small">
                                    <thead>
                                        <tr class="table-light">
                                            <th class="ps-3">Tanggal</th>
                                            <th class="text-center">Nota</th>
                                            <th class="text-end pe-3">Omset</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($penjualan_harian as $harian): ?>
                                            <tr>
                                                <td class="ps-3"><?= date('d M Y', strtotime($harian['TANGGAL_PENJUALAN'])) ?></td>
                                                <td class="text-center bg-light font-weight-bold"><?= $harian['jumlah_transaksi'] ?></td>
                                                <td class="text-end pe-3 text-success font-weight-bold">Rp <?= number_format($harian['omset_harian'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-users text-warning me-2"></i>Staf & Karyawan Aktif</span>
                            <span class="badge bg-dark rounded-pill"><?= count($karyawan) ?> Orang</span>
                        </div>
                        <div class="card-body p-0">
                            <?php if(empty($karyawan)): ?>
                                <p class="text-muted text-center py-5 my-0">Tidak ada staf terdaftar di cabang ini.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table mb-0 align-middle table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Nama Staf</th>
                                                <th>Jabatan</th>
                                                <th>No. Telepon</th>
                                                <th class="text-end pe-3">Gaji Pokok</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($karyawan as $k): ?>
                                                <tr>
                                                    <td class="ps-3">
                                                        <strong><?= esc($k['NAMA_KARYAWAN']) ?></strong>
                                                        <div class="text-muted small">ID: <?= $k['ID_KARYAWAN'] ?></div>
                                                    </td>
                                                    <td><span class="badge bg-secondary"><?= esc($k['JABATAN']) ?></span></td>
                                                    <td><?= esc($k['NO_TELEPON'] ?? '-') ?></td>
                                                    <td class="text-end pe-3 text-dark font-weight-bold">Rp <?= number_format($k['GAJI'], 0, ',', '.') ?></td>
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
    </div>
</div>
</body>
</html>