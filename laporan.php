<?php
session_start();
include 'koneksi.php';

$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$query_laporan = mysqli_query($koneksi, "SELECT tgl_masuk, COUNT(*) as jml_pesanan, SUM(total_biaya) as total_pemasukan 
    FROM pesanan 
    WHERE status_produksi = 'Selesai' 
    AND MONTH(tgl_masuk) = '$bulan_pilihan' 
    AND YEAR(tgl_masuk) = '$tahun_pilihan'
    GROUP BY tgl_masuk 
    ORDER BY tgl_masuk DESC");

$query_total = mysqli_query($koneksi, "SELECT SUM(total_biaya) as total_akhir FROM pesanan 
    WHERE status_produksi = 'Selesai' 
    AND MONTH(tgl_masuk) = '$bulan_pilihan' 
    AND YEAR(tgl_masuk) = '$tahun_pilihan'");

$data_total = mysqli_fetch_assoc($query_total);
$total_akhir = $data_total['total_akhir'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Jasa Jahit</title>
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
                <h1 class="top-bar-title">Laporan Pemasukan (Kas)</h1>
            </header>

            <div class="content-body">
                <div class="filter-section">
                    <div class="filter-controls">
                        <div class="filter-wrapper">
                            <div class="filter-card">
    <select class="select-input" id="filterBulan">
    <option value="01" <?php echo ($bulan_pilihan == '01') ? 'selected' : ''; ?>>Januari</option>
    <option value="02" <?php echo ($bulan_pilihan == '02') ? 'selected' : ''; ?>>Februari</option>
    <option value="03" <?php echo ($bulan_pilihan == '03') ? 'selected' : ''; ?>>Maret</option>
    <option value="04" <?php echo ($bulan_pilihan == '04') ? 'selected' : ''; ?>>April</option>
    <option value="05" <?php echo ($bulan_pilihan == '05') ? 'selected' : ''; ?>>Mei</option>
    <option value="06" <?php echo ($bulan_pilihan == '06') ? 'selected' : ''; ?>>Juni</option>
    <option value="07" <?php echo ($bulan_pilihan == '07') ? 'selected' : ''; ?>>Juli</option>
    <option value="08" <?php echo ($bulan_pilihan == '08') ? 'selected' : ''; ?>>Agustus</option>
    <option value="09" <?php echo ($bulan_pilihan == '09') ? 'selected' : ''; ?>>September</option>
    <option value="10" <?php echo ($bulan_pilihan == '10') ? 'selected' : ''; ?>>Oktober</option>
    <option value="11" <?php echo ($bulan_pilihan == '11') ? 'selected' : ''; ?>>November</option>
    <option value="12" <?php echo ($bulan_pilihan == '12') ? 'selected' : ''; ?>>Desember</option>
</select>

    <input type="number" id="filterTahun" class="select-input" value="<?php echo $tahun_pilihan; ?>" style="width: 100px;">

    <button class="btn-filter" onclick="jalankanFilter()">
        <i class="fa-solid fa-magnifying-glass"></i> Filter
    </button>
</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-section">
                    <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pesanan</th>
                            <th>Jumlah Pesanan Selesai</th>
                            <th>Total Pendapatan</th>
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
                                <td>Rp <?php echo number_format($row['total_pemasukan'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php } 
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center;'>Tidak ada pesanan selesai pada periode ini.</td></tr>";
                        } ?>
                    </tbody>
                    </table>
                </div>

                <div class="summary-card">
                    <div class="summary-content">
                        <img src="assets/icon-koin.png" alt="icon" class="summary-icon"> 
                        <div class="summary-text">
                            <p>Total Kas Masuk Bulan Ini</p>
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
<script src="js/laporan.js"></script>
</body>
</html>