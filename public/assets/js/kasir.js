let keranjang = [];
let totalBelanja = 0;

// Fungsi klik item menu
function tambahKeKeranjang(id, nama, harga) {
    let itemAda = keranjang.find(i => i.id_menu === id);
    
    if (itemAda) {
        itemAda.jumlah += 1;
    } else {
        keranjang.push({ id_menu: id, nama: nama, harga: harga, jumlah: 1 });
    }
    renderKeranjang();
}

function hapusItem(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}

// Menampilkan list di HTML
function renderKeranjang() {
    const cartList = document.getElementById('cart-list');
    cartList.innerHTML = '';
    totalBelanja = 0;

    keranjang.forEach((item, index) => {
        let subtotal = item.harga * item.jumlah;
        totalBelanja += subtotal;

        cartList.innerHTML += `
            <li class="cart-item">
                <div>
                    <strong>${item.nama}</strong><br>
                    <small>${item.jumlah} x Rp ${item.harga.toLocaleString('id-ID')}</small>
                </div>
                <div>
                    Rp ${subtotal.toLocaleString('id-ID')}
                    <button onclick="hapusItem(${index})">X</button>
                </div>
            </li>
        `;
    });

    document.getElementById('total-harga').innerText = 'Rp ' + totalBelanja.toLocaleString('id-ID');
}

// Kirim data ke Controller menggunakan AJAX (Fetch API)
function prosesPembayaran() {
    if (keranjang.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    const metodeBayar = document.getElementById('metode-bayar').value;

    const dataKirim = {
        keranjang: keranjang,
        total: totalBelanja,
        metode_bayar: metodeBayar
    };

    // Ubah teks tombol biar kasir tahu proses sedang jalan
    const btn = document.getElementById('btn-bayar');
    btn.innerText = "Memproses...";
    btn.disabled = true;

    fetch('/kasir/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dataKirim)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message + "\nNomor Nota: " + data.trx_id);
            keranjang = []; // Kosongkan keranjang
            renderKeranjang();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Terjadi kesalahan koneksi!");
    })
    .finally(() => {
        btn.innerText = "Bayar Sekarang";
        btn.disabled = false;
    });
}