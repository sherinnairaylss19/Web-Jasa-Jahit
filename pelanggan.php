<?php
session_start();
include 'koneksi.php'; 

// Query Total Pelanggan
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelanggan");
$data_total = mysqli_fetch_assoc($query_total);

// Query Pelanggan Baru Bulan Ini
$sql_baru = "SELECT COUNT(DISTINCT pelanggan.id_pelanggan) as baru 
             FROM pelanggan 
             JOIN pesanan ON pelanggan.id_pelanggan = pesanan.id_pelanggan 
             WHERE MONTH(pesanan.tgl_masuk) = MONTH(CURRENT_DATE()) 
             AND YEAR(pesanan.tgl_masuk) = YEAR(CURRENT_DATE())";

$query_baru = mysqli_query($koneksi, $sql_baru);
$data_baru = mysqli_fetch_assoc($query_baru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Riwayat Pelanggan</title>
    <link rel="stylesheet" href="css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container">
        <nav class="sidebar">
            <?php include 'sidebar.php'; ?>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1 class="top-bar-title">Riwayat Pelanggan</h1>
        </div>
        
        <div class="dashboard-body">
            <!-- Statistik -->
            <div class="stats-container" style="display: flex; gap: 20px; margin-bottom: 25px;">
                <div class="card" style="background: white; padding: 20px; border-radius: 10px; flex: 1; border: 1px solid #ddd;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <i class="fas fa-users fa-2x" style="color: #2D5E55;"></i>
                        <div>
                            <p style="color:#666; margin:0;">Total Pelanggan</p>
                            <h2 style="margin:0;"><?php echo $data_total['total'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card" style="background: white; padding: 20px; border-radius: 10px; flex: 1; border: 1px solid #ddd;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <i class="fas fa-user-plus fa-2x" style="color: #2D5E55;"></i>
                        <div>
                            <p style="color:#666; margin:0;">Pelanggan Baru</p>
                            <h2 style="margin:0;"><?php echo $data_baru['baru'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pencarian -->
            <div class="toolbar" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="search-box" style="background:white; padding:10px; border-radius:8px; border:1px solid #ddd; width: 50%; display: flex; align-items: center;">
                    <i class="fas fa-search" style="color:#999;"></i>
                    <input type="text" id="inputCari" onkeyup="fungsiCari()" placeholder="Cari Nama Pelanggan..." style="border:none; outline:none; width:100%; margin-left:10px;">
                </div>
            </div>    

            <!-- Tabel Data -->
            <div class="table-section" style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #eee;">
                            <th style="padding: 12px; text-align: left;">No</th>
                            <th style="padding: 12px; text-align: left;">Nama</th>
                            <th style="padding: 12px; text-align: left;">No HP</th>
                            <th style="padding: 12px; text-align: left;">Alamat</th>
                            <th style="padding: 12px; text-align: left;">Tgl Masuk</th>
                            <th style="padding: 12px; text-align: left;">Jenis Pesanan</th>
                            <th style="padding: 12px; text-align: left;">Total</th>
                            <th style="padding: 12px; text-align: center;">Status</th>
                            <th style="padding: 12px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, s.* 
                                FROM pelanggan p 
                                LEFT JOIN pesanan s ON p.id_pelanggan = s.id_pelanggan 
                                ORDER BY s.tgl_masuk DESC";
                        
                        $result = mysqli_query($koneksi, $sql);

                        if ($result) {
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)) {
                                $status = $row['status_produksi'] ?? 'N/A'; 
                                $class_status = 'status-' . str_replace(' ', '-', strtolower($status));
                                
                                echo "<tr>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . $no++ . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['no_hp']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['alamat_lengkap']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . ($row['tgl_masuk'] ? date('d M Y', strtotime($row['tgl_masuk'])) : '-') . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['jenis_pesanan'] ?? '-') . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>Rp " . number_format($row['total_biaya'] ?? 0, 0, ',', '.') . "</td>";
                                
                                // Kolom Status berwarna
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee; text-align:center;'>
                                        <span class='badge-status $class_status'>" . htmlspecialchars($status) . "</span>
                                      </td>";
                                
                                // Kolom Aksi
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee; text-align:center;'>
                                        <a href='tambah-pesanan.php?id_pelanggan=".$row['id_pelanggan']."' 
                                           style='background:#2D5E55; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-size:12px; display:inline-block;'>
                                           Pesan Lagi
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' style='padding:20px; text-align:center; color:red;'>Kesalahan Database: " . mysqli_error($koneksi) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function fungsiCari() {
            const input = document.getElementById('inputCari');
            const filter = input.value.toLowerCase();
            const tr = document.querySelectorAll('tbody tr');

            tr.forEach(row => {
                const nama = row.cells[1].innerText.toLowerCase();
                row.style.display = nama.includes(filter) ? "" : "none";
            });
        }
    </script>
</body>
</html>