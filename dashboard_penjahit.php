<?php
session_start();
include 'koneksi.php'; 

// Proteksi halaman: Pastikan hanya penjahit yang sudah login bisa masuk
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'penjahit') {
    header("Location: index.php");
    exit();
}

// 1. Hitung Total Semua Pesanan
$total_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan");
$res_total = mysqli_fetch_assoc($total_q);

// 2. Hitung Pesanan Dalam Proses (Kolom: status_produksi)
$proses_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Proses'");
$res_proses = mysqli_fetch_assoc($proses_q);

// 3. Hitung Pesanan Selesai (Kolom: status_produksi)
$selesai_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Selesai'");
$res_selesai = mysqli_fetch_assoc($selesai_q);

// 4. Hitung Pesanan Telat (Kolom: status_produksi)
$telat_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Telat'");
$res_telat = mysqli_fetch_assoc($telat_q);

// 5. Total Pemasukan Hari Ini (Berdasarkan tgl_masuk dan total_biaya)
$pemasukan_q = mysqli_query($koneksi, "SELECT SUM(total_biaya) as total FROM pesanan WHERE DATE(tgl_masuk) = CURDATE()");
$res_pemasukan = mysqli_fetch_assoc($pemasukan_q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jasa Jahit</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Dashboard Penjahit</h1>
            </header>

            <div class="dashboard-body">
                <div class="stats-container">
                    <div class="card blue">
                        <img src="assets/icon-mesin-jahit.jpeg" alt="Icon" width="40">
                        <p>Total Pesanan</p>
                        <h2>10</h2>
                    </div>
                    <div class="card yellow">
                        <img src="assets/icon-benang.png" alt="Icon" width="40">
                        <p>Dalam Proses</p>
                        <h2>6</h2>
                    </div>
                    <div class="card cyan">
                        <img src="assets/icon-selesai.png" alt="Icon" width="40">
                        <p>Selesai</p>
                        <h2>3</h2>
                    </div>
                    <div class="card red">
                        <img src="assets/icon-deadline.png" alt="Icon" width="40">
                        <p>Deadline Dekat</p>
                        <h2>2</h2>
                    </div>
                </div>

                <div class="table-section">
                    <h3>Pesanan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Tgl Tenggat</th>
                                <th>Total Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sherin</td>
                                <td>Permak Celana</td>
                                <td><span class="status selesai">Selesai</span></td>
                                <td>23 April</td>
                                <td>30.000</td>
                            </tr>
                            <tr>
                                <td>Dina</td>
                                <td>Permak Blouse</td>
                                <td><span class="status proses">Proses</span></td>
                                <td>25 April</td>
                                <td>250.000</td>
                            </tr>
                            <tr>
                                <td>Rani</td>
                                <td>Jahit Dress</td>
                                <td><span class="status selesai">Selesai</span></td>
                                <td>18 Maret</td>
                                <td>90.000</td>
                            </tr>
                            <tr>
                                <td>Dimas</td>
                                <td>Bordir Logo</td>
                                <td><span class="status proses">Proses</span></td>
                                <td>10 Maret</td>
                                <td>90.000</td>
                            </tr>
                            <tr>
                                <td>Hendra</td>
                                <td>Permak Celana</td>
                                <td><span class="status telat">Telat</span></td>
                                <td>10 Maret</td>
                                <td>45.000</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="button-container">
                    <a href="pesanan.php" class="btn-all">Lihat Semua Pesanan</a>
                </div>

                <div class="income-card">
                    <div class="income-icon">
                        <img src="assets/icon-koin.png" alt="Coin Icon">
                    </div>
                    <div class="income-info">
                        <p class="income-label">Total Pemasukan Hari Ini</p>
                        <h2 class="income-amount">Rp 505.000</h2>
                    </div>
                </div>

            </div> </div> </div> </body>
</html>