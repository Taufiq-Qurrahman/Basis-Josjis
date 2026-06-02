<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/stok.css') ?>">
</head>
<body>
    <div class="header-stok">
        <h2>Monitoring Stok Nasional Tehkota</h2>
        <a href="<?= base_url('dashboard') ?>" class="btn-home">Dashboard</a>
    </div>

    <div class="container">
        <div class="filter-box">
            <label>Pilih Wilayah/Cabang:</label>
            <select id="filterCabang" onchange="filterTabel()">
                <option value="ALL">Semua Cabang</option>
                <?php foreach($cabang as $c): ?>
                    <option value="<?= $c['NAMA_CABANG'] ?>"><?= $c['NAMA_CABANG'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <table id="tabelStok">
            <thead>
                <tr>
                    <th>Cabang</th>
                    <th>Nama Menu/Bahan</th>
                    <th>Sisa Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($stok as $s): ?>
                <?php 
                    $isLow = $s['JUMLAH_STOK'] <= $s['BATAS_MINIMAL'];
                    $statusClass = $isLow ? 'status-danger' : 'status-safe';
                ?>
                <tr>
                    <td><?= $s['NAMA_CABANG'] ?></td>
                    <td><?= $s['NAMA_MENU'] ?></td>
                    <td>
                        <input type="number" class="input-stok" value="<?= $s['JUMLAH_STOK'] ?>" 
                               id="stok-<?= $s['ID_CABANG'] ?>-<?= $s['ID_MENU'] ?>">
                    </td>
                    <td><span class="badge <?= $statusClass ?>"><?= $isLow ? 'Habis/Menipis' : 'Aman' ?></span></td>
                    <td>
                        <button class="btn-save" onclick="updateStok('<?= $s['ID_CABANG'] ?>', '<?= $s['ID_MENU'] ?>')">Update</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="<?= base_url('assets/js/stok.js') ?>"></script>
</body>
</html>