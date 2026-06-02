<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <h3>Tehkota HQ</h3>
        <a href="<?= base_url('dashboard') ?>" class="active">Dashboard</a>
        <a href="<?= base_url('cabang') ?>">Master Cabang</a>
        <a href="<?= base_url('auth/logout') ?>" style="color: #ff4d4d;">Logout</a>
    </div>

    <div class="main-content">
        <h1>Dashboard Analytics</h1>
        
        <div class="stats-grid">
            <div class="card card-green">
                <h3>Omzet Hari Ini</h3>
                <p>Rp <?= number_format($omzet, 0, ',', '.') ?></p>
            </div>
            <div class="card card-red">
                <h3>Stok Kritis</h3>
                <p><?= $stok_warning ?> Item</p>
            </div>
        </div>

        <div class="dashboard-layout">
            <div class="chart-section">
                <h2>Grafik Penjualan Bulanan per Cabang</h2>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="shipping-section">
                <h2>Status Pengiriman Barang</h2>
                <ul class="shipping-list">
                    <?php if(empty($pengiriman_aktif)): ?>
                        <li>Tidak ada pengiriman aktif.</li>
                    <?php endif; ?>
                    <?php foreach($pengiriman_aktif as $kirim): ?>
                    <li>
                        <strong><?= $kirim['ID_PENGIRIMAN'] ?></strong><br>
                        <small><?= $kirim['ASAL'] ?> ➔ <?= $kirim['TUJUAN'] ?></small><br>
                        <span class="badge-status"><?= $kirim['STATUS_KIRIM'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // 1. Ambil data mentah JSON dari Controller Dashboard
        const dataDariDatabase = <?= $data_grafik ?>;
        
        // 2. Pecah data menjadi Label (Nama Cabang)
        const labelsCabang = dataDariDatabase.length > 0 ? dataDariDatabase.map(item => item.NAMA_CABANG) : ['Belum Ada Data'];
        
        // 3. 🛠️ FITUR AMAN & ADAPTIF: Otomatis mendeteksi nilai angka dari database
        const dataOmset = dataDariDatabase.length > 0 ? dataDariDatabase.map(item => {
            // Cek semua kemungkinan penamaan kolom nominal dari query Model Anda
            if (item.TOTAL !== undefined && item.TOTAL !== null) return Number(item.TOTAL);
            if (item.total !== undefined && item.total !== null) return Number(item.total);
            if (item.TOTAL_OMSET !== undefined && item.TOTAL_OMSET !== null) return Number(item.TOTAL_OMSET);
            if (item.TOTAL_BAYAR !== undefined && item.TOTAL_BAYAR !== null) return Number(item.TOTAL_BAYAR);
            if (item.OMSET !== undefined && item.OMSET !== null) return Number(item.OMSET);
            if (item.omset !== undefined && item.omset !== null) return Number(item.omset);
            
            // Sistem Deteksi Cadangan Otomatis: jika nama kolom berbeda, cari properti pertama yang bertipe angka
            for (let key in item) {
                if (key !== 'ID_CABANG' && key !== 'NAMA_CABANG' && !isNaN(item[key]) && item[key] > 0) {
                    return Number(item[key]);
                }
            }
            return 0;
        }) : [0];

        // 4. Render Chart.js secara realtime
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsCabang,
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: dataOmset,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>