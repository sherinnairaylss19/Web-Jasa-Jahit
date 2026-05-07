<?php
session_start();
include 'koneksi.php'; 

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'penjahit') {
    header("Location: index.php");
    exit();
}

$total_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE is_deleted = 0");
$res_total = mysqli_fetch_assoc($total_q);

$proses_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Proses' AND is_deleted = 0");
$res_proses = mysqli_fetch_assoc($proses_q);

$selesai_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Selesai' AND is_deleted = 0");
$res_selesai = mysqli_fetch_assoc($selesai_q);

$telat_q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pesanan WHERE status_produksi='Telat' AND is_deleted = 0");
$res_telat = mysqli_fetch_assoc($telat_q);

$bulan_ini_q = mysqli_query($koneksi, "SELECT SUM(total_biaya) as total FROM pesanan 
                                       WHERE status_produksi = 'Selesai' 
                                       AND is_deleted = 0
                                       AND MONTH(tgl_masuk) = MONTH(CURDATE()) 
                                       AND YEAR(tgl_masuk) = YEAR(CURDATE())");
$res_bulan_ini = mysqli_fetch_assoc($bulan_ini_q);

$query_tabel = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap 
                                       FROM pesanan 
                                       JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                       WHERE pesanan.is_deleted = 0
                                       ORDER BY pesanan.id_pesanan DESC LIMIT 5");
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
                        <h2><?php echo $res_total['total']; ?></h2>
                    </div>
                    <div class="card yellow">
                        <img src="assets/icon-benang.png" alt="Icon" width="40">
                        <p>Dalam Proses</p>
                        <h2><?php echo $res_proses['total']; ?></h2>
                    </div>
                    <div class="card cyan">
                        <img src="assets/icon-selesai.png" alt="Icon" width="40">
                        <p>Selesai</p>
                        <h2><?php echo $res_selesai['total']; ?></h2>
                    </div>
                    <div class="card red">
                        <img src="assets/icon-deadline.png" alt="Icon" width="40">
                        <p>Pesanan Telat</p>
                        <h2><?php echo $res_telat['total'] ?? 0; ?></h2>
                    </div>
                </div>

                <div class="table-section">
                    <h3>Pesanan Terbaru</h3>
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
                        <?php while($row = mysqli_fetch_assoc($query_tabel)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($row['jenis_pesanan']); ?></td>
                                <td>
                                    <?php 
                                        $status_raw = $row['status_produksi']; 
                                        $status_clean = str_replace(' ', '-', strtolower($status_raw)); 
                                        echo "<span class='status status-$status_clean'>" . htmlspecialchars($status_raw) . "</span>";
                                    ?>
                                </td>
                                <td><?php echo date('d M', strtotime($row['tgl_tenggat'])); ?></td>
                                <td><?php echo number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="button-container">
                        <a href="pesanan.php" class="btn-all">Lihat Semua Pesanan</a>
                    </div>
                </div>

                <div class="income-card">
                    <div class="income-icon">
                        <img src="assets/icon-koin.png" alt="Coin Icon">
                    </div>
                    <div class="income-info">
                        <p class="income-label">Total Pemasukan Bulan Ini </p>
                        <h2 class="income-amount">
                            Rp <?php echo number_format($res_bulan_ini['total'] ?? 0, 0, ',', '.'); ?>
                        </h2>
                    </div>
                </div>

            </div> 
        </div> 
    </div> 
</body>
</html>