function filterTabel() {
    let input = document.getElementById("filterCabang").value;
    let table = document.getElementById("tabelStok");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[0];
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (input === "ALL" || txtValue === input) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function updateStok(idCabang, idMenu) {
    let val = document.getElementById(`stok-${idCabang}-${idMenu}`).value;
    
    let formData = new FormData();
    formData.append('id_cabang', idCabang);
    formData.append('id_menu', idMenu);
    formData.append('jumlah', val);

    fetch('/stok/update_cepat', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Refresh untuk update badge warna
    });
}