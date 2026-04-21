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

$query = mysqli_query($koneksi, "SELECT pesanan.*, 
                                        pelanggan.nama_lengkap, pelanggan.no_hp, pelanggan.alamat_lengkap,
                                        pembayaran.uang_muka, pembayaran.sisa_bayar
                                 FROM pesanan 
                                 JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                 LEFT JOIN pembayaran ON pesanan.id_pesanan = pembayaran.id_pesanan
                                 WHERE pesanan.id_pesanan = '$id_pesanan'");
$data = mysqli_fetch_assoc($query);

$uang_muka = $data['uang_muka'] ?? 0;
$sisa_bayar = $data['sisa_bayar'] ?? $data['total_biaya'];
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
        <?php include 'sidebar.php'; ?>
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
                            <th>Ukuran</th>
                            <th>Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= $data['jenis_pesanan']; ?></td>
                            <td><?= nl2br($data['catatan']); ?></td>
                            <td>
                                <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; line-height: 1.6;">
                                    <li><strong>Bahu:</strong> <?= $data['lebar_bahu'] ?? '-'; ?></li>
                                    <li><strong>Dada:</strong> <?= $data['lingkar_dada'] ?? '-'; ?></li>
                                    <li><strong>Lengan:</strong> <?= $data['panjang_lengan'] ?? '-'; ?></li>
                                    <li><strong>Baju:</strong> <?= $data['panjang_baju'] ?? '-'; ?></li>
                                </ul>
                            </td>
                            <td>Rp <?= number_format($data['total_biaya'], 0, ',', '.'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div class="nota-footer">
                    <div class="footer-left">
                        <p><strong>Total Keseluruhan:</strong> Rp <?= number_format($data['total_biaya'], 0, ',', '.'); ?></p>
                        <p><strong>Uang Muka (DP)</strong> Rp <?= number_format($uang_muka, 0, ',', '.'); ?></p>
                        <p style="color: #d32f2f;"><strong>Sisa Tagihan</strong></td> Rp <?= number_format($sisa_bayar, 0, ',', '.'); ?></p>
                    </tr>
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