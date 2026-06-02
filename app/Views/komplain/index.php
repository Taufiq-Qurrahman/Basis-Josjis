<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h2 class="mt-4"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Keluhan / Komplain</h2>
    <p class="text-muted">Halaman ini digunakan untuk menampilkan keluhan atau komplain pelanggan terkait cabang dan layanan Teh Kota.</p>

    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="alert alert-info" role="alert">
                <strong>Info:</strong> Fitur komplain sudah tersedia. Tambahkan logika dan tampilan komplain pelanggan sesuai kebutuhan aplikasi Anda.
            </div>
            <p class="mb-0">Untuk sementara, halaman ini berfungsi sebagai placeholder rute. Anda dapat membuka daftar komplain di sini nanti.</p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
