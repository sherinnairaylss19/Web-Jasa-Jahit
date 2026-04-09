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
            <div class="profile">
                <img src="<?php echo $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
                <span id="user-nama"><?php echo $_SESSION['nama']; ?></span>
            </div>

            <ul>
                <li><a href="dashboard_penjahit.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-desktop"></i>Dashboard</a></li>
                <li class="active"><i class="fa-solid fa-pen-to-square"></i>Pesanan</li>
                <li><a href="pelanggan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-user-group"></i>Pelanggan</a></li>
                <li><a href="pembayaran.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-wallet"></i>Pembayaran</a></li>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i>Logout</a></li>
            </ul>
        </nav>

            <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Pesanan</h1>
            </header>

            <div class="content-body">
                <div class="toolbar">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari Nama Pelanggan">
                    </div>
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
                                    <a href="detail-pesanan.html" class="btn-detail">Detail</a>
                                    <a href="edit-pesanan.html" class="btn-edit">Edit</a>
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
</body>
</html>