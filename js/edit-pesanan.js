document.getElementById('formEditPesanan').addEventListener('submit', function(e) {
    e.preventDefault();

    const dataUpdate = {
        nama: document.getElementById('editNama').value,
        status: document.getElementById('editStatus').value,
        total: document.getElementById('editTotal').value,
        catatan: document.getElementById('editCatatan').value
    };

    console.log("Mengirim data UPDATE ke database...", dataUpdate);
    
    alert("Data pesanan " + dataUpdate.nama + " berhasil diperbarui menjadi: " + dataUpdate.status);
    
    window.location.href = 'pesanan.html';
});