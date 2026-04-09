<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}
if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}
$id_pesanan = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.no_hp, pelanggan.alamat_lengkap 
                                 FROM pesanan 
                                 JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                 WHERE pesanan.id_pesanan = '$id_pesanan'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pesanan.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Tambahan style khusus print agar sidebar tidak ikut tercetak */
        @media print {
            .sidebar, .header-breadcrumb, .btn-print, .nota-actions {
                display: none !important;
            }
            .main-content {
                margin: 0;
                padding: 0;
            }
            .container {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="profile">
                <img src="<?= $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
                <span id="user-nama"><?= $_SESSION['nama']; ?></span>
            </div>
            <ul>
                <li><a href="dashboard_penjahit.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-desktop"></i>Dashboard</a></li>
                <li class="active"><a href="pesanan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-pen-to-square"></i> Pesanan</a></li>
                <li><a href="pelanggan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-user-group"></i>Pelanggan</a></li>
                <li><a href="pembayaran.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-wallet"></i>Pembayaran</a></li>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i>Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Detail Pesanan</p>
            </div>

            <div class="nota-container">
                <div class="nota-header">
                    <h1>Nota Pesanan</h1>
                    <h2>Toko Jahit Dua Saudara</h2>
                    <p>Jl. Widasari Kec. Widasari Kab. Indramayu, Jawa Barat</p>
                </div>

                <div class="nota-info">
                    <div class="info-left">
                        <p><strong>Nama:</strong> <?= $data['nama_lengkap']; ?></p>
                        <p><strong>No Telp:</strong> <?= $data['no_hp']; ?></p>
                        <p><strong>Alamat:</strong> <?= $data['alamat_lengkap']; ?></p>
                    </div>
                    <div class="info-right">
                        <p><strong>Tanggal Masuk:</strong> <?= date('d F Y', strtotime($data['tgl_masuk'])); ?></p>
                        <p><strong>Tanggal Tenggat:</strong> <?= date('d F Y', strtotime($data['tgl_tenggat'])); ?></p>
                    </div>
                </div>

                <table class="nota-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Pesanan</th>
                            <th>Catatan / Detail</th>
                            <th>Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= $data['jenis_pesanan']; ?></td>
                            <td><?= nl2br($data['catatan']); ?></td>
                            <td>Rp <?= number_format($data['total_biaya'], 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="nota-footer">
                    <div class="footer-left">
                        <p><strong>Total Keseluruhan:</strong> Rp <?= number_format($data['total_biaya'], 0, ',', '.'); ?></p>
                    </div>
                    <div class="footer-right">
                        <p>Hormat kami,</p>
                        <br><br>
                        <p>Toko Dua Bersaudara</p>
                    </div>
                </div>

                <div class="nota-actions">
                    <button class="btn-print" onclick="window.print()">
                        <i class="fa-solid fa-print"></i> Cetak Nota
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>