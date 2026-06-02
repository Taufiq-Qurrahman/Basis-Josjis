<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Desain Layar Kasir Utama - Dioptimalkan agar Seimbang dan Proporsional */
    .product-grid { max-height: 78vh; overflow-y: auto; padding-right: 5px; }
    .product-card { border: none; border-radius: 10px; background: #f8f9fa; cursor: pointer; transition: 0.2s; position: relative; }
    .product-card:hover { background: #e9ecef; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .product-card.habis { opacity: 0.5; cursor: not-allowed; background: #eaedf0; }
    .badge-stok { position: absolute; top: 5px; right: 5px; font-size: 0.75rem; padding: 3px 6px; border-radius: 20px; }
    
    /* Layout Komponen Keranjang Kanan Sticky & Flex Balance */
    .cart-container { position: sticky; top: 20px; height: 82vh; display: flex; flex-direction: column; background: white; border-radius: 15px; }
    .cart-items { flex-grow: 1; overflow-y: auto; padding: 15px; max-height: 38vh; }
    .cart-item { border-bottom: 1px solid #eee; padding: 10px 0; }
    .money-input { font-size: 1.5rem; font-weight: bold; color: #198754; text-align: right; }
    .kembalian-display { font-size: 1.2rem; font-weight: bold; }

    /* Kustomisasi scrollbar internal agar visual kasir lebih rapi */
    .product-grid::-webkit-scrollbar, .cart-items::-webkit-scrollbar { width: 6px; }
    .product-grid::-webkit-scrollbar-thumb, .cart-items::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }

    /* Elemen struk disembunyikan total dari layar utama kasir */
    #area-struk-cetak { 
        display: none !important; 
    }
</style>

<div class="row g-4">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-cash-register text-success me-2"></i>Kasir POS</h3>
            <input type="text" id="search" class="form-control w-25" placeholder="Cari menu...">
        </div>
        
        <div class="row product-grid g-3" id="menuList">
            <?php foreach ($menu as $m): 
                $stokReal = $m['stok'] ?? 0;
                $isHabis = ($stokReal <= 0);
            ?>
            <div class="col-md-3 menu-card" data-name="<?= strtolower($m['nama_menu']) ?>">
                <div class="card product-card shadow-sm p-3 text-center <?= $isHabis ? 'habis' : '' ?>" 
                     <?php $id_menu_asli = $m['id_menu'] ?? $m['id'] ?? ''; ?>
                     onclick="<?= $isHabis ? "alert('Stok menu ini sudah habis!')" : "addToCart('".$id_menu_asli."', '".$m['nama_menu']."', ".($m['harga'] ?? $m['harga_jual'] ?? 0).", ".$stokReal.")" ?>">
                     <img src="<?= base_url('uploads/menu/' . ($m['gambar'] ?? 'default.png')) ?>" alt="<?= $m['nama_menu'] ?>" class="card-img-top" style="height: 120px; object-fit: cover; border-radius: 10px;">
                     
                    <span class="badge badge-stok <?= $isHabis ? 'bg-danger' : 'bg-secondary' ?>">
                        Stok: <?= $stokReal ?>
                    </span>

                    <div class="fw-bold mb-1 text-dark mt-2"><?= $m['nama_menu'] ?></div>
                    <div class="text-success fw-bold">Rp <?= number_format($m['harga'] ?? $m['harga_jual'] ?? 0, 0, ',', '.') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card cart-container shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3 fs-5 border-bottom"><i class="fa-solid fa-basket-shopping text-primary me-2"></i>Keranjang Pesanan</div>
            
            <div class="cart-items" id="cartContent">
                <p class="text-center text-muted mt-5">Belum ada pesanan</p>
            </div>
            
            <div class="card-footer bg-light border-0 p-4 mt-auto">
                <div class="d-flex justify-content-between h3 fw-bold mb-3 text-dark border-bottom pb-2">
                    <span>Total</span>
                    <span id="totalText" class="text-primary">Rp 0</span>
                </div>
                
                <div class="mb-2">
                    <label class="small fw-bold text-muted">Pelanggan / Member</label>
                    <select id="pembeli_id" class="form-select">
                        <option value="">-- Non-Member (Umum) --</option>
                        <?php foreach($pembeli_list as $p): ?>
                            <option value="<?= $p['id_pembeli'] ?>">
                                <?= $p['nama_pembeli'] ?? $p['nama'] ?> (Poin: <?= $p['point'] ?? 0 ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted">Metode Pembayaran</label>
                    <select id="metode" class="form-select">
                        <?php foreach($pembayaran as $p): ?>
                            <option value="<?= $p['id_jenis_pembayaran'] ?>"><?= $p['nama_jenis_pembayaran'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1">Uang Diterima dari Customer (Rp)</label>
                    <input type="number" id="uang_bayar" class="form-control form-control-lg money-input" placeholder="0" oninput="hitungKembalian()">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold text-muted">Kembalian:</span>
                    <span id="kembalianText" class="kembalian-display text-muted">Rp 0</span>
                </div>

                <button class="btn btn-success w-100 py-3 fw-bold shadow-sm fs-5" id="btn-proses" onclick="bayar()">PROSES & CETAK STRUK</button>
            </div>
        </div>
    </div>
</div>

<div id="area-struk-cetak">
    <div style="text-align: center;">
        <h2 style="margin: 0; font-weight: bold; font-size: 18px;">TEH KOTA</h2>
        <p style="margin: 2px 0;">Outlet Cabang Pusat</p>
        <p style="margin: 2px 0;">Jl. Raya Utama No. 123, Kota</p>
        <p style="margin: 2px 0;">Telp: 0812-3456-7890</p>
    </div>
    
    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
    
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <tr><td style="width: 35%; padding: 1px 0;">Tanggal</td><td style="width: 5%;">:</td><td id="struk-waktu"></td></tr>
        <tr><td style="padding: 1px 0;">No. Nota</td><td>:</td><td id="struk-id"></td></tr>
        <tr><td style="padding: 1px 0;">Kasir</td><td>:</td><td id="struk-kasir"><?= session()->get('username') ?? 'Staff' ?></td></tr>
        <tr><td style="padding: 1px 0;">Pelanggan</td><td>:</td><td id="struk-pembeli">Umum</td></tr>
        <tr><td style="padding: 1px 0;">Metode</td><td>:</td><td id="struk-metode"></td></tr>
    </table>
    
    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
    
    <table id="struk-items-list" style="width: 100%; border-collapse: collapse; font-size: 12px;">
    </table>
    
    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
    
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <tr style="font-weight: bold;">
            <td style="padding: 3px 0;">TOTAL BELANJA</td>
            <td style="text-align: right;" id="struk-total">Rp 0</td>
        </tr>
        <tr>
            <td style="padding: 3px 0;">UANG TUNAI</td>
            <td style="text-align: right;" id="struk-uang-diterima">Rp 0</td>
        </tr>
        <tr style="font-weight: bold;">
            <td style="padding: 3px 0;">KEMBALIAN</td>
            <td style="text-align: right;" id="struk-kembalian">Rp 0</td>
        </tr>
    </table>
    
    <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
    
    <div style="text-align: center; margin-top: 15px;">
        <p style="margin: 3px 0; font-weight: bold;">Terima Kasih</p>
        <p style="margin: 3px 0;">Atas Kunjungan Anda</p>
        <p style="margin: 3px 0; font-size: 10px;">Instagram: @tehkota.official</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let cart = [];
let totalHargaBelanja = 0;

function addToCart(id, nama, harga, stokMaks) {
    let item = cart.find(i => i.id === id);
    if (item) { 
        if (item.qty >= stokMaks) {
            alert("Tidak bisa menambah barang. Batas maksimal stok toko tercapai!");
            return;
        }
        item.qty++; 
    } else { 
        cart.push({id, nama, harga, qty: 1, maxStok: stokMaks}); 
    }
    render();
}

function render() {
    const cont = document.getElementById('cartContent');
    totalHargaBelanja = 0;
    
    if (cart.length === 0) { 
        cont.innerHTML = '<p class="text-center text-muted mt-5">Belum ada pesanan</p>'; 
        document.getElementById('totalText').innerText = 'Rp 0';
        hitungKembalian();
        return; 
    }
    
    cont.innerHTML = cart.map((item, index) => {
        totalHargaBelanja += item.harga * item.qty;
        return `
            <div class="cart-item d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold text-dark">${item.nama}</div>
                    <small class="text-muted">${item.qty} x Rp ${item.harga.toLocaleString('id-ID')}</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-3">Rp ${(item.qty * item.harga).toLocaleString('id-ID')}</span>
                    <button class="btn btn-sm btn-outline-danger" onclick="cart.splice(${index},1);render()"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>`;
    }).join('');
    
    document.getElementById('totalText').innerText = 'Rp ' + totalHargaBelanja.toLocaleString('id-ID');
    hitungKembalian();
}

function hitungKembalian() {
    const inputUang = document.getElementById('uang_bayar');
    const textKembalian = document.getElementById('kembalianText');
    const uangBayar = parseInt(inputUang.value) || 0;
    const kembalian = uangBayar - totalHargaBelanja;
    
    if (totalHargaBelanja === 0) {
        textKembalian.innerText = "Rp 0";
        textKembalian.className = "kembalian-display text-muted";
        return;
    }

    if (uangBayar === 0) {
        textKembalian.innerText = "Menunggu pembayaran...";
        textKembalian.className = "kembalian-display text-warning";
    } else if (kembalian < 0) {
        textKembalian.innerText = "Kurang: Rp " + Math.abs(kembalian).toLocaleString('id-ID');
        textKembalian.className = "kembalian-display text-danger";
    } else {
        textKembalian.innerText = "Rp " + kembalian.toLocaleString('id-ID');
        textKembalian.className = "kembalian-display text-success";
    }
}

function bayar() {
    if (cart.length === 0) return alert("Keranjang belanja masih kosong!");
    
    const inputUang = document.getElementById('uang_bayar').value;
    const nominalBayar = parseInt(inputUang) || 0;
    
    if (nominalBayar < totalHargaBelanja) {
        return alert("Uang yang diterima dari pelanggan masih kurang!");
    }

    const btnProses = document.getElementById('btn-proses');
    btnProses.disabled = true;
    btnProses.innerText = "Memproses...";
    
    fetch('<?= base_url('kasir/simpan') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ 
            items: cart, 
            pembayaran: document.getElementById('metode').value,
            pembeli_id: $('#pembeli_id').val()
        })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            eksekusiCetakStruk(res.trx_id, nominalBayar);
        } else { 
            alert("Gagal: " + res.message); 
            btnProses.disabled = false;
            btnProses.innerText = "PROSES & CETAK STRUK";
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi masalah koneksi sistem database.");
        btnProses.disabled = false;
        btnProses.innerText = "PROSES & CETAK STRUK";
    });
}

function eksekusiCetakStruk(trxId, nominalBayar) {
    document.getElementById('struk-waktu').innerText = new Date().toLocaleString('id-ID');
    document.getElementById('struk-id').innerText = trxId;
    
    const selectMetode = document.getElementById('metode');
    document.getElementById('struk-metode').innerText = selectMetode.options[selectMetode.selectedIndex].text;
    
    const selectPembeli = document.getElementById('pembeli_id');
    document.getElementById('struk-pembeli').innerText = selectPembeli.value !== "" ? selectPembeli.options[selectPembeli.selectedIndex].text.split('(')[0] : "Umum";

    document.getElementById('struk-total').innerText = "Rp " + totalHargaBelanja.toLocaleString('id-ID');
    document.getElementById('struk-uang-diterima').innerText = "Rp " + nominalBayar.toLocaleString('id-ID');
    document.getElementById('struk-kembalian').innerText = "Rp " + (nominalBayar - totalHargaBelanja).toLocaleString('id-ID');
    
    let barisItemHtml = "";
    cart.forEach(item => {
        barisItemHtml += `
            <tr>
                <td colspan="3" style="font-weight: bold; padding-top: 5px;">${item.nama}</td>
            </tr>
            <tr>
                <td style="width: 15%;">${item.qty}x</td>
                <td style="width: 45%;">@ ${item.harga.toLocaleString('id-ID')}</td>
                <td style="width: 40%; text-align: right;">Rp ${(item.qty * item.harga).toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    document.getElementById('struk-items-list').innerHTML = barisItemHtml;

    const isiStrukHtml = document.getElementById('area-struk-cetak').innerHTML;
    const jendelaCetak = window.open('', '_blank', 'width=350,height=600');
    
    const isiHalamanCetak = `
        <html>
        <head>
            <title>Cetak Struk Transaksi</title>
            <style>
                body { font-family: 'Courier New', Courier, monospace; font-size: 12px; color: #000; padding: 10px; margin: 0; width: 280px; }
                table { width: 100%; border-collapse: collapse; }
                td { vertical-align: top; }
            </style>
        </head>
        <body>
            ${isiStrukHtml}
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 500);
                }
            <\/script>
        </body>
        </html>
    `;
    
    jendelaCetak.document.write(isiHalamanCetak);
    jendelaCetak.document.close();

    // Reset Variabel Keranjang
    const btnProses = document.getElementById('btn-proses');
    btnProses.disabled = false;
    btnProses.innerText = "PROSES & CETAK STRUK";
    cart = [];
    totalHargaBelanja = 0;
    document.getElementById('uang_bayar').value = '';
    render();
    
    // Memberikan jeda aman 1 detik untuk memproses refresh halaman
    setTimeout(function() {
        location.reload();
    }, 1000);
}

document.getElementById('search').addEventListener('input', function(e) {
    let q = e.target.value.toLowerCase();
    document.querySelectorAll('.menu-card').forEach(c => {
        c.style.display = c.dataset.name.includes(q) ? '' : 'none';
    });
});
</script>
<?= $this->endSection() ?>