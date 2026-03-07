function muatLaporan() {
    const filter = document.getElementById('filterBulan').value;
    const db = JSON.parse(localStorage.getItem('db_duasaudara')) || [];
    const tbody = document.getElementById('reportTable');
    let rekap = {}, totalBulan = 0;

    db.forEach(item => {
        if (item.tglISO.startsWith(filter)) {
            const tgl = item.tglDisplay;
            if (!rekap[tgl]) rekap[tgl] = { selesai: 0, uang: 0 };
            if (item.total - item.dp <= 0) rekap[tgl].selesai += 1;
            rekap[tgl].uang += item.dp;
            totalBulan += item.dp;
        }
    });

    tbody.innerHTML = '';
    Object.keys(rekap).sort().forEach((tgl, i) => {
        tbody.innerHTML += `<tr><td>${i+1}</td><td>${tgl}</td><td>${rekap[tgl].selesai}</td><td>Rp ${rekap[tgl].uang.toLocaleString()}</td></tr>`;
    });
    document.getElementById('grandTotal').innerText = `Rp ${totalBulan.toLocaleString()}`;
}

window.onload = () => {
    const d = new Date();
    document.getElementById('filterBulan').value = `${d.getFullYear()}-${(d.getMonth()+1).toString().padStart(2,'0')}`;
    muatLaporan();
};