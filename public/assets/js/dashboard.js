document.addEventListener("DOMContentLoaded", function() {
    // Siapkan array kosong untuk label dan data
    let labelsCabang = [];
    let dataPenjualan = [];

    // Looping data dari PHP
    grafikDataRaw.forEach(item => {
        labelsCabang.push(item.NAMA_CABANG);
        dataPenjualan.push(item.TOTAL_PENJUALAN);
    });

    // Inisialisasi Chart.js
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelsCabang,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: dataPenjualan,
                backgroundColor: '#3498db',
                borderRadius: 5
            }]
        },
        options: { responsive: true }
    });
});