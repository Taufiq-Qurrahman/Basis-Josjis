<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4">Form Input Pembelian Bahan Baku</h2>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Tambah Pasokan Logistik Baru</li></ol>

    <div class="card shadow-sm max-width-700">
        <div class="card-header bg-primary text-white">Form Transaksi Belanja Supplier</div>
        <div class="card-body">
            <form action="<?= base_url('pembelian/simpan'); ?>" method="post">
                
                <div class="mb-3">
                    <label class="form-label">Pilih Supplier Penjual</label>
                    <select name="id_supplier" class="form-select" required>
                        <option value="">-- Pilih Supplier --</option>
                        <?php foreach($supplier as $s): ?>
                            <option value="<?= $s['ID_SUPPLIER']; ?>"><?= $s['NAMA_SUPPLIER']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cabang yang Menerima Barang</label>
                    <select name="id_cabang" class="form-select" required>
                        <option value="">-- Pilih Cabang --</option>
                        <?php foreach($cabang as $c): ?>
                            <option value="<?= $c['ID_CABANG']; ?>"><?= $c['NAMA_CABANG']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item / Bahan Baku</label>
                        <select name="id_menu" class="form-select" required>
                            <option value="">-- Pilih Item --</option>
                            <?php foreach($menu as $m): ?>
                                <option value="<?= $m['ID_MENU']; ?>"><?= $m['NAMA_MENU']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Jumlah (Qty)</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" min="1" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Harga Satuan (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="Harga Beli" min="0" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success px-4">💾 Simpan Pembelian</button>
                    <a href="<?= base_url('pembelian'); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>