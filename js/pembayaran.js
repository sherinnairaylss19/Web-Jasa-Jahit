function muatTabel() {
    const db = JSON.parse(localStorage.getItem('db_duasaudara')) || [];
    const cari = document.getElementById('cari').value.toLowerCase();
    const tbody = document.getElementById('tabelBody');
    tbody.innerHTML = '';

    db.forEach((item, i) => {
        if (item.nama.toLowerCase().includes(cari)) {
            const sisa = item.total - item.dp;
            const statusClass = sisa <= 0 ? 'status-lunas' : 'status-dp';
            const statusText = sisa <= 0 ? 'Lunas' : 'DP';

            tbody.innerHTML += `
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${item.nama}</strong></td>
                    <td>${item.tglDisplay}</td>
                    <td>Rp ${item.total.toLocaleString()}</td>
                    <td>Rp ${item.dp.toLocaleString()}</td>
                    <td>Rp ${sisa.toLocaleString()}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td class="btn-action">
                        <button onclick="editData(${i})" style="background:red; color:white; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">Edit</button>
                    </td>
                </tr>`;
        }
    });
}

function simpanData() {
    const editIdx = document.getElementById('editIdx').value;
    const tglRaw = document.getElementById('tgl').value;
    const data = {
        nama: document.getElementById('nama').value,
        tglISO: tglRaw,
        tglDisplay: new Date(tglRaw).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'}),
        total: parseInt(document.getElementById('total').value) || 0,
        dp: parseInt(document.getElementById('dp').value) || 0
    };

    let db = JSON.parse(localStorage.getItem('db_duasaudara')) || [];
    if (editIdx === "") db.push(data); else db[editIdx] = data;
    
    localStorage.setItem('db_duasaudara', JSON.stringify(db));
    tutupModal(); muatTabel();
}

function editData(i) {
    const db = JSON.parse(localStorage.getItem('db_duasaudara'));
    const item = db[i];
    document.getElementById('editIdx').value = i;
    document.getElementById('nama').value = item.nama;
    document.getElementById('tgl').value = item.tglISO;
    document.getElementById('total').value = item.total;
    document.getElementById('dp').value = item.dp;
    document.getElementById('modal').style.display = 'flex';
}

function bukaModal() { document.getElementById('editIdx').value = ""; document.getElementById('modal').style.display = 'flex'; }
function tutupModal() { document.getElementById('modal').style.display = 'none'; }
window.onload = muatTabel;