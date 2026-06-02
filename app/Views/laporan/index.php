<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Pusat Laporan & Output Data</h2>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Sistem Informasi Manajemen Teh Kota</li>
    </ol>

    <ul class="nav nav-pills mb-4 bg-light p-2 rounded shadow-sm">
        <li class="nav-item">
            <a class="nav-link active font-weight-bold" href="<?= base_url('laporan'); ?>">📋 Riwayat Transaksi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= base_url('laporan/labarugi'); ?>">💰 Laba Rugi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= base_url('laporan/stokinventori'); ?>">📦 Stok Inventori</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-secondary" href="<?= base_url('laporan/auditlog'); ?>">🔒 Audit Log</a>
        </li>
    </ul>

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <form action="/laporan" method="get" class="row g-2">
                <div class="col-auto">
                    <input type="date" name="tanggal" class="form-control" value="<?= $tgl_pilih; ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <div class="card bg-success text-white d-inline-block shadow-sm">
                <div class="card-body py-2 px-4">
                    <small class="d-block opacity-75">Total Omzet Hari Ini</small>
                    <h4 class="mb-0 font-weight-bold">Rp <?= number_format($totalOmzet, 0, ',', '.'); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-table me-1"></i> Data Penjualan Tanggal: <?= date('d F Y', strtotime($tgl_pilih)); ?>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Transaksi</th>
                        <th>Waktu</th>
                        <th>Kasir / Karyawan</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($penjualan)): $no = 1; foreach ($penjualan as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><strong><?= $row['ID_PENJUALAN']; ?></strong></td>
                            <td><?= date('H:i:s', strtotime($row['TANGGAL_PENJUALAN'])); ?> WIB</td>
                            <td><?= $row['NAMA_KARYAWAN'] ?? 'Tidak Diketahui'; ?></td>
                            <td>Rp <?= number_format($row['TOTAL'], 0, ',', '.'); ?></td>
                            <td>
                                <button class="btn btn-info btn-sm btn-detail text-white" data-id="<?= $row['ID_PENJUALAN']; ?>">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada transaksi pada tanggal ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Transaksi #<span id="labelTrx"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Menu</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="isiDetail"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.btn-detail', function() {
        const id = $(this).data('id');
        $('#labelTrx').text(id);
        $('#isiDetail').html('<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm text-info"></div> Memuat...</td></tr>');
        
        $('#modalDetail').modal('show');

        $.get('/laporan/detail/' + id, function(data) {
            let html = '';
            if (data.length > 0) {
                data.forEach(item => {
                    html += `<tr>
                        <td class="ps-3">${item.NAMA_MENU ?? 'Menu Terhapus'}</td>
                        <td class="text-center">${item.JUMLAH_PENJUALAN}</td>
                        <td class="text-end">Rp ${parseInt(item.HARGA_SATUAN).toLocaleString('id-ID')}</td>
                        <td class="text-end pe-3">Rp ${parseInt(item.SUBTOTAL).toLocaleString('id-ID')}</td>
                    </tr>`;
                });
            } else {
                html = '<tr><td colspan="4" class="text-center py-3 text-danger">Data detail tidak ditemukan.</td></tr>';
            }
            $('#isiDetail').html(html);
        }).fail(function() {
            $('#isiDetail').html('<tr><td colspan="4" class="text-center py-3 text-danger">Gagal memuat data.</td></tr>');
        });
    });
});
</script>
<?= $this->endSection(); ?>