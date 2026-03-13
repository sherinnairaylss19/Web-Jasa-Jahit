document.addEventListener('DOMContentLoaded', function() {
    // 1. Inisialisasi Elemen
    const btnFilter = document.querySelector('.btn-filter');
    const btnPrint = document.querySelector('.btn-print');
    const tableRows = document.querySelectorAll('tbody tr');

    // 2. Fungsi Filter (Berdasarkan Tanggal atau isi tabel)
    // Mengikuti gaya logika pesanan.js yang menggunakan perulangan row
    if (btnFilter) {
        btnFilter.addEventListener('click', function() {
            // Contoh sederhana: Kita bisa menambahkan input pencarian 
            // atau memfilter berdasarkan rentang tanggal tertentu.
            alert("Memproses filter laporan...");
            
            // Logika filter jika ingin menyaring berdasarkan teks tertentu (sama dengan pesanan.js)
            /*
            tableRows.forEach(row => {
                const tanggal = row.cells[1].textContent.toLowerCase();
                if (tanggal.includes('apr')) { // Contoh filter bulan April
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            */
        });
    }

    // 3. Fungsi Cetak Laporan
    if (btnPrint) {
        btnPrint.addEventListener('click', function() {
            // Membuka dialog print browser
            window.print();
        });
    }

    // 4. Integrasi Sidebar (Memastikan navigasi tetap aktif)
    const sidebarItems = document.querySelectorAll('.sidebar li');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            const link = this.querySelector('a');
            if (link) {
                window.location.href = link.getAttribute('href');
            }
        });
    });
});