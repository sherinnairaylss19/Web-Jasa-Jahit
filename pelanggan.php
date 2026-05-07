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
            <h1 class="top-bar-title">Pelanggan</h1>
        </div>
        
        <div class="dashboard-body">
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

                        <div class="toolbar" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="search-box" style="background:white; padding:10px; border-radius:8px; border:1px solid #ddd; width: 50%; display: flex; align-items: center;">
                    <i class="fas fa-search" style="color:#999;"></i>
                    <input type="text" id="inputCari" onkeyup="fungsiCari()" placeholder="Cari Nama Pelanggan..." style="border:none; outline:none; width:100%; margin-left:10px;">
                </div>
            </div>    

                        <div class="table-section" style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <h3>Riwayat Pelanggan</h3>
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #eee;">
                            <th style="padding: 12px; text-align: left;">No</th>
                            <th style="padding: 12px; text-align: left;">Nama</th>
                            <th style="padding: 12px; text-align: left;">No HP</th>
                            <th style="padding: 12px; text-align: left;">Alamat</th>
                            <th style="padding: 12px; text-align: left;">Tgl Masuk</th>
                            <th style="padding: 12px; text-align: left;">Jenis Pesanan</th>
                            <th style="padding: 12px; text-align: left;">Ukuran</th>
                            <th style="padding: 12px; text-align: left;">Catatan</th>                             <th style="padding: 12px; text-align: left;">Total</th>
                            <th style="padding: 12px; text-align: center;">Status</th>
                            <th style="padding: 12px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, s.*                                 FROM pelanggan p 
                                LEFT JOIN pesanan s ON p.id_pelanggan = s.id_pelanggan 
                                ORDER BY s.tgl_masuk DESC";
                        
                        $result = mysqli_query($koneksi, $sql);

                        $jenis_atasan = ['Kemeja', 'Atasan', 'Gamis', 'Kebaya', 'Seragam'];
                        $jenis_celana = ['Celana', 'Celana Jeans'];

                        if ($result) {
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)) {
                                $status = $row['status_produksi'] ?? 'N/A'; 
                                $class_status = 'status-' . str_replace(' ', '-', strtolower($status));
                                $jenis = $row['jenis_pesanan'] ?? '';

                                // Bangun isi kolom ukuran
                                ob_start();
                                echo "<ul style='list-style:none; padding:0; margin:0; font-size:12px; line-height:1.7;'>";
                                if (in_array($jenis, $jenis_atasan)) {
                                    echo "<li><strong>Bahu:</strong> " . ($row['lebar_bahu'] ? htmlspecialchars($row['lebar_bahu']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Dada:</strong> " . ($row['lingkar_dada'] ? htmlspecialchars($row['lingkar_dada']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Lengan:</strong> " . ($row['panjang_lengan'] ? htmlspecialchars($row['panjang_lengan']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Panjang:</strong> " . ($row['panjang_baju'] ? htmlspecialchars($row['panjang_baju']).' cm' : '-') . "</li>";
                                } elseif (in_array($jenis, $jenis_celana)) {
                                    echo "<li><strong>Pinggang:</strong> " . ($row['lingkar_pinggang'] ? htmlspecialchars($row['lingkar_pinggang']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Pinggul:</strong> " . ($row['lingkar_pinggul'] ? htmlspecialchars($row['lingkar_pinggul']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Paha:</strong> " . ($row['lingkar_paha'] ? htmlspecialchars($row['lingkar_paha']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Panjang:</strong> " . ($row['panjang_baju'] ? htmlspecialchars($row['panjang_baju']).' cm' : '-') . "</li>";
                                } else {
                                    $ukuran = [
                                        'Bahu'     => $row['lebar_bahu'] ?? '',
                                        'Dada'     => $row['lingkar_dada'] ?? '',
                                       'Lengan'   => $row['panjang_lengan'] ?? '',
                                        'Panjang'  => $row['panjang_baju'] ?? '',
                                        'Pinggang' => $row['lingkar_pinggang'] ?? '',
                                        'Pinggul'  => $row['lingkar_pinggul'] ?? '',
                                        'Paha'     => $row['lingkar_paha'] ?? '',
                                    ];
                                    $ada_ukuran = false;
                                    foreach ($ukuran as $label => $val) {
                                        if ($val != '') {
                                            echo "<li><strong>$label:</strong> " . htmlspecialchars($val) . " cm</li>";
                                            $ada_ukuran = true;
                                        }
                                    }
                                    if (!$ada_ukuran) echo "<li>-</li>";
                                }
                                echo "</ul>";
                                $kolom_ukuran = ob_get_clean();
                                
                                echo "<tr>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . $no++ . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['no_hp']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['alamat_lengkap']) . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . ($row['tgl_masuk'] ? date('d M Y', strtotime($row['tgl_masuk'])) : '-') . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['jenis_pesanan'] ?? '-') . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>" . $kolom_ukuran . "</td>";
                                
                                // TAMBAHAN KOLOM CATATAN DI BODY
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee; font-size: 13px; color: #555;'>";
                                echo !empty($row['catatan_tambahan']) ? htmlspecialchars($row['catatan_tambahan']) : "-";
                                echo "</td>";

                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee;'>Rp " . number_format($row['total_biaya'] ?? 0, 0, ',', '.') . "</td>";
                                
                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee; text-align:center;'>
                                        <span class='badge-status $class_status'>" . htmlspecialchars($status) . "</span>
                                      </td>";
                                
                                $id_pesanan = $row['id_pesanan'] ?? '';
                                $link_pesan = "tambah-pesanan.php?id_pelanggan=" . $row['id_pelanggan'];
                                if (!empty($id_pesanan)) {
                                    $link_pesan .= "&dari_pesanan=" . $id_pesanan;
                                }

                                echo "<td style='padding: 12px; border-bottom: 1px solid #eee; text-align:center;'>
                                        <a href='$link_pesan' 
                                           style='background:#2D5E55; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-size:12px; display:inline-block;'>
                                           Pesan Lagi
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='11' style='padding:20px; text-align:center; color:red;'>Kesalahan Database: " . mysqli_error($koneksi) . "</td></tr>";
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