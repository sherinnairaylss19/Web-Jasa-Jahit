document.getElementById('formPesanan').addEventListener('submit', function(e) {
    e.preventDefault();

    const dataPesanan = {
        nama: document.getElementById('nama').value,
        noHp: document.getElementById('noHp').value,
        tglMasuk: document.getElementById('tglMasuk').value,
        total: document.getElementById('totalBiaya').value,
        jenis: document.getElementById('jenisPesanan').value
    };

    console.log("Data Berhasil Disimpan:", dataPesanan);
    alert("Pesanan Baru Berhasil Disimpan!");
    
    window.location.href = 'pesanan.html';
});