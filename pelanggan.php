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
            <!-- Statistik -->
            <div class="stats-container">
                <div class="card">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <i class="fas fa-users fa-2x" style="color: #2D5E55;"></i>
                        <div>
                            <p style="color:#666; margin:0;">Total Pelanggan</p>
                            <h2 style="margin:0;"><?php echo $data_total['total'] ?? 0; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card">
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
            <div class="toolbar">
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="inputCari" onkeyup="fungsiCari()" placeholder="Cari Nama Pelanggan...">
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-section">
                <h3>Riwayat Pelanggan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Alamat</th>
                            <th>Tgl Masuk</th>
                            <th>Tgl Tenggat</th>
                            <th>Jenis Pesanan</th>
                            <th>Catatan</th>
                            <th>Ukuran</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, s.* 
                                FROM pelanggan p 
                                LEFT JOIN pesanan s ON p.id_pelanggan = s.id_pelanggan 
                                WHERE s.is_deleted = 0 OR s.id_pesanan IS NULL
                                ORDER BY s.tgl_masuk DESC";
                        
                        $result = mysqli_query($koneksi, $sql);

                        $jenis_atasan = ['Kemeja', 'Atasan', 'Gamis', 'Kebaya', 'Seragam'];
                        $jenis_celana = ['Celana', 'Celana Jeans'];

                        if ($result) {
                            $no = 1;
                            while($row = mysqli_fetch_assoc($result)) {
                                $jenis = $row['jenis_pesanan'] ?? '';

                                // Logika status Telat — sama persis dengan pesanan.php
                                $status_tampil = $row['status_produksi'] ?? 'N/A';
                                if ($status_tampil === 'Proses' && !empty($row['tgl_tenggat']) && $row['tgl_tenggat'] < date('Y-m-d')) {
                                    $status_tampil = 'Telat';
                                }
                                $status_clean = str_replace(' ', '-', strtolower($status_tampil));

                                // Kolom ukuran — sama persis dengan pesanan.php
                                ob_start();
                                echo "<ul style='list-style:none; padding:0; margin:0; font-size:13px; line-height:1.7;'>";
                                if (in_array($jenis, $jenis_atasan)) {
                                    echo "<li><strong>Bahu:</strong> "    . ($row['lebar_bahu']      ? htmlspecialchars($row['lebar_bahu']).' cm'      : '-') . "</li>";
                                    echo "<li><strong>Dada:</strong> "    . ($row['lingkar_dada']    ? htmlspecialchars($row['lingkar_dada']).' cm'    : '-') . "</li>";
                                    echo "<li><strong>Lengan:</strong> "  . ($row['panjang_lengan']  ? htmlspecialchars($row['panjang_lengan']).' cm'  : '-') . "</li>";
                                    echo "<li><strong>Panjang:</strong> " . ($row['panjang_baju']    ? htmlspecialchars($row['panjang_baju']).' cm'    : '-') . "</li>";
                                } elseif (in_array($jenis, $jenis_celana)) {
                                    echo "<li><strong>Pinggang:</strong> ". ($row['lingkar_pinggang']? htmlspecialchars($row['lingkar_pinggang']).' cm': '-') . "</li>";
                                    echo "<li><strong>Pinggul:</strong> " . ($row['lingkar_pinggul'] ? htmlspecialchars($row['lingkar_pinggul']).' cm' : '-') . "</li>";
                                    echo "<li><strong>Paha:</strong> "    . ($row['lingkar_paha']    ? htmlspecialchars($row['lingkar_paha']).' cm'    : '-') . "</li>";
                                    echo "<li><strong>Panjang:</strong> " . ($row['panjang_baju']    ? htmlspecialchars($row['panjang_baju']).' cm'    : '-') . "</li>";
                                } else {
                                    $ukuran = [
                                        'Bahu'     => $row['lebar_bahu']       ?? '',
                                        'Dada'     => $row['lingkar_dada']     ?? '',
                                        'Lengan'   => $row['panjang_lengan']   ?? '',
                                        'Panjang'  => $row['panjang_baju']     ?? '',
                                        'Pinggang' => $row['lingkar_pinggang'] ?? '',
                                        'Pinggul'  => $row['lingkar_pinggul']  ?? '',
                                        'Paha'     => $row['lingkar_paha']     ?? '',
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
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat_lengkap']); ?></td>
                                    <td><?php echo $row['tgl_masuk']  ? date('d M Y', strtotime($row['tgl_masuk']))  : '-'; ?></td>
                                    <td><?php echo $row['tgl_tenggat'] ? date('d M Y', strtotime($row['tgl_tenggat'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_pesanan'] ?? '-'); ?></td>
                                    <td><?php echo nl2br(htmlspecialchars($row['catatan'] ?? '-')); ?></td>
                                    <td><?php echo $kolom_ukuran; ?></td>
                                    <td><?php echo number_format($row['total_biaya'] ?? 0, 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="status_produksi <?php echo $status_clean; ?>">
                                            <?php echo htmlspecialchars($status_tampil); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $id_pesanan = $row['id_pesanan'] ?? '';
                                        $link_pesan = "tambah-pesanan.php?id_pelanggan=" . $row['id_pelanggan'];
                                        if (!empty($id_pesanan)) {
                                            $link_pesan .= "&dari_pesanan=" . $id_pesanan;
                                        }
                                        ?>
                                        <a href="<?php echo $link_pesan; ?>" class="btn-pesan">Pesan Lagi</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='12' style='padding:20px; text-align:center; color:red;'>Kesalahan Database: " . mysqli_error($koneksi) . "</td></tr>";
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
                const nama = row.cells[1]?.innerText.toLowerCase() ?? '';
                row.style.display = nama.includes(filter) ? "" : "none";
            });
        }
    </script>
</body>
</html>