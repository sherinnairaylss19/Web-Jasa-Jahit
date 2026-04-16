<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman:
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pemilik') {
    header("Location: dashboard.php?pesan=akses_ditolak");
    exit();
}

$query_laporan = mysqli_query($koneksi, "SELECT tgl_masuk, COUNT(*) as jml_pesanan, SUM(total_biaya) as pemasukan_harian 
    FROM pesanan 
    WHERE status_produksi = 'Selesai' 
    GROUP BY tgl_masuk 
    ORDER BY tgl_masuk DESC");

$query_total = mysqli_query($koneksi, "SELECT SUM(total_biaya) as total_bulan_ini FROM pesanan 
    WHERE status_produksi = 'Selesai' 
    AND MONTH(tgl_masuk) = MONTH(CURRENT_DATE()) 
    AND YEAR(tgl_masuk) = YEAR(CURRENT_DATE())");

$data_total = mysqli_fetch_assoc($query_total);
$total_akhir = $data_total['total_bulan_ini'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Laporan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/laporan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            .sidebar, .filter-section, .btn-print, .top-bar { display: none !important; }
            .main-content { margin-left: 0 !important; width: 100% !important; }
            .container { display: block !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Laporan Pemasukan</h1>
            </header>

            <div class="content-body">
                <div class="filter-section">
                    <div class="filter-controls">
                        <div class="filter-wrapper">
                            <div class="filter-card">
                                <select class="select-input">
                                    <option>Bulan Ini (<?php echo date('F'); ?>)</option>
                                </select>
                                <div class="date-display">
                                    01 <?php echo date('M'); ?> - <?php echo date('t M Y'); ?>
                                </div>
                                <button class="btn-filter">
                                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-section">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pesanan Selesai</th>
                                <th>Total Pemasukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($query_laporan) > 0) {
                                while($row = mysqli_fetch_assoc($query_laporan)) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['tgl_masuk'])); ?></td>
                                    <td><?php echo $row['jml_pesanan']; ?> Unit</td>
                                    <td>Rp <?php echo number_format($row['pemasukan_harian'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php } 
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada pesanan dengan status 'Selesai' bulan ini.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>

                <div class="summary-card">
                    <div class="summary-content">
                        <img src="assets/icon-koin.png" alt="icon" class="summary-icon"> 
                        <div class="summary-text">
                            <p>Total Pemasukan Bulan Ini</p>
                            <h2>Rp <?php echo number_format($total_akhir, 0, ',', '.'); ?></h2>
                        </div>
                    </div>
                </div>

                <div class="action-footer">
                    <button class="btn-print" onclick="window.print()">
                        <i class="fa-solid fa-print"></i> Cetak Laporan (PDF)
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>