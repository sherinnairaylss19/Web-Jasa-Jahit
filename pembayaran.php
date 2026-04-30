<?php
session_start();
// Pastikan file koneksi.php sudah benar atau tulis manual seperti ini
$koneksi = mysqli_connect("localhost", "root", "", "toko-jahit");

$sql = "SELECT 
            pembayaran.id_pembayaran,
            pelanggan.nama_lengkap, 
            pesanan.tgl_masuk, 
            pesanan.total_biaya,
            pembayaran.uang_muka,
            pembayaran.sisa_bayar,
            pembayaran.status_bayar
        FROM pembayaran
        JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan
        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan";

$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="css/pembayaran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Pembayaran</h1>
            </header>

            <div class="content-body">
                <div class="toolbar">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari Nama Pelanggan">
                    </div>
                </div>

                <div class="table-section">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Uang Muka</th>
                                <th>Sisa Bayar</th>
                                <th>Status Bayar</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= $row['nama_lengkap']; ?></strong></td>
                                <td><?= $row['tgl_masuk']; ?></td>
                                <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['uang_muka'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['sisa_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $status = strtolower($row['status_bayar']);
                                    $class_status = ($status == 'lunas') ? 'selesai' : 'proses';
                                    ?>
                                    <span class="status <?= $class_status; ?>"><?= strtoupper($status); ?></span>
                                </td>
                                <td class="aksi-buttons">
                                    <div class="button-group">
                                        <a href="edit-bayar.php?id=<?= $row['id_pembayaran']; ?>" class="btn-edit-bayar">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    const searchInput = document.querySelector('.search-box input');
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        const tr = document.querySelectorAll('tbody tr');

        tr.forEach(row => {
            const nama = row.cells[1].innerText.toLowerCase();
            if (nama.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
</body>
</html>