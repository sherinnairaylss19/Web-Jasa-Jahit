document.addEventListener('DOMContentLoaded', function() {
    console.log("Laporan JS Ready!");

    const selectBulan = document.querySelector('.select-input');
    if (selectBulan) {
        selectBulan.addEventListener('change', function() {
            console.log("Filter diubah ke: " + this.value);
        });
    }
});

function jalankanFilter() {
   
    const bulan = document.getElementById('filterBulan').value;
    const tahun = document.getElementById('filterTahun').value;

    window.location.href = `laporan.php?bulan=${bulan}&tahun=${tahun}`;
}

function cetakLaporan() {
    
    const bulanNama = document.querySelector('.select-input').value;
    const originalTitle = document.title;

    document.title = "Laporan_Keuangan_" + bulanNama.replace(/\s+/g, '_');

    window.print();

    setTimeout(() => {
        document.title = originalTitle;
    }, 1000);
}

function cariDiTabel() {
    const input = document.getElementById("inputCari");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("tabelLaporan");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1]; 
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}