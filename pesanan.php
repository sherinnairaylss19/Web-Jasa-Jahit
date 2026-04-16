<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$query = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.alamat_lengkap, pelanggan.no_hp 
                                 FROM pesanan 
                                 JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                 ORDER BY tgl_tenggat ASC");

$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    // Jika ada pencarian, tambahkan kondisi WHERE
    $query_str = "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.alamat_lengkap, pelanggan.no_hp 
                  FROM pesanan 
                  JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                  WHERE pelanggan.nama_lengkap LIKE '%$keyword%' 
                  ORDER BY tgl_tenggat ASC";
} else {
    // Jika tidak ada pencarian, tampilkan semua
    $query_str = "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.alamat_lengkap, pelanggan.no_hp 
                  FROM pesanan 
                  JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
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
                <input type="text" name="cari" placeholder="Cari Nama Pelanggan" value="<?= isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
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
                            <td><?= $row['nama_lengkap']; ?></td> <td><?= $row['no_hp']; ?></td>
                            <td><?= $row['alamat_lengkap']; ?></td>
                            <td><?= $row['jenis_pesanan']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl_masuk'])); ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl_tenggat'])); ?></td>
                            <td><?= $row['catatan']; ?></td>
                            <td><?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="status_produksi <?= strtolower($row['status_produksi']); ?>">
                                    <?= $row['status_produksi']; ?>
                                </span>
                            </td>
                            <td class="aksi-buttons">
                                <div class="button-group">
                                    <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>" class="btn-detail">Detail</a>
                                   <a href="edit-pesanan.php?id=<?= $row['id_pesanan']; ?>" class="btn-edit">Edit</a>
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