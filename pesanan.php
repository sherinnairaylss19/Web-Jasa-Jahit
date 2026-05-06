<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    $query_str = "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.alamat_lengkap, pelanggan.no_hp 
                  FROM pesanan 
                  JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                  WHERE pelanggan.nama_lengkap LIKE '%$keyword%' AND pesanan.is_deleted = 0
                  ORDER BY tgl_tenggat ASC";
} else {
    $query_str = "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.alamat_lengkap, pelanggan.no_hp 
                  FROM pesanan 
                  JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                  WHERE pesanan.is_deleted = 0 
                  ORDER BY tgl_tenggat ASC";
}

$query = mysqli_query($koneksi, $query_str);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Pesanan</h1>
            </header>

            <div class="content-body">
                <div class="toolbar">
                    <form action="" method="GET" class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="cari" placeholder="Cari Nama Pelanggan" value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                        <button type="submit" style="display:none;">Cari</button> 
                    </form>
                    <a href="tambah-pesanan.php" class="btn-add">+ Tambahkan Pesanan</a>
                </div>

                <div class="table-section">
                    <h3>Pesanan Keseluruhan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>Jenis Pesanan</th>
                                <th>Tgl Masuk</th>
                                <th>Tgl Tenggat</th>
                                <th>Catatan</th>
                                <th>Total</th>
                                <th>Status Produksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($query)) : 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['nama_lengkap']); ?></td> 
                                <td><?= htmlspecialchars($row['no_hp']); ?></td>
                                <td><?= htmlspecialchars($row['alamat_lengkap']); ?></td>
                                <td><?= htmlspecialchars($row['jenis_pesanan']); ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_masuk'])); ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_tenggat'])); ?></td>
                                <td><?= htmlspecialchars($row['catatan']); ?></td>
                                <td><?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status_produksi <?= strtolower(str_replace(' ', '-', $row['status_produksi'])); ?>">
                                        <?= htmlspecialchars($row['status_produksi']); ?>
                                    </span>
                                </td>
                                <td class="aksi-buttons">
                                    <div class="button-group">
                                        <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>" class="btn-detail">Nota</a>
                                        <a href="edit-pesanan.php?id=<?= $row['id_pesanan']; ?>" class="btn-edit">Edit</a>
                                        <a href="tambah-pembayaran.php?id=<?= $row['id_pesanan']; ?>" class="btn-bayar">Bayar</a>
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
                row.style.display = nama.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>
</html>