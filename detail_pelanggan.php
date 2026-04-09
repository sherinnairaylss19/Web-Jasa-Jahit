<?php
session_start();
include 'koneksi.php';

// 1. Ambil ID dari URL
$id_pelanggan = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id_pelanggan)) {
    header("Location: pelanggan.php");
    exit();
}

// 2. Query ambil data pelanggan berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data pelanggan tidak ditemukan!'); window.location='pelanggan.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan - <?php echo $data['nama_lengkap']; ?></title>
    <link rel="stylesheet" href="css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container" style="display: flex; min-height: 100vh; width: 100%; margin: 0; padding: 0;">
        <nav class="sidebar">
            <div class="profile">
                <img src="<?php echo $_SESSION['foto']; ?>" id="user-foto" alt="User">
                <span id="user-nama"><?php echo $_SESSION['nama']; ?></span>
            </div>
            <ul>
                <li><a href="dashboard_pemilik.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-desktop"></i> Dashboard</a></li>
                <li><a href="pesanan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-pen-to-square"></i> Pesanan</a></li>
                <li class="active"><i class="fa-solid fa-user-group"></i> Pelanggan</li>
                <li><a href="pembayaran.php" style="color:white; text-decoration:none;"><i class="fas fa-wallet"></i> Pembayaran</a></li>
                <li><a href="laporan.php" style="color:white; text-decoration:none;"><i class="fas fa-clock"></i> Laporan</a></li>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i> Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <div class="top-bar">
                <a href="pelanggan.php" style="text-decoration: none; color: white; font-size: 14px;">
                    <i class="fa-solid fa-chevron-left"></i> Kembali Pelanggan / <strong>Detail Pelanggan</strong>
                </a>
            </div>
            
            <div class="dashboard-body">
                <div style="background: white; border: 1px solid #ccc; border-radius: 4px; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="display: flex;">
                        <div style="flex: 1; padding: 20px; border-right: 1px solid #ccc;">
                            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Detail Pelanggan</h3>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 10px 5px; color: #555; width: 150px; border-bottom: 1px solid #f9f9f9;">Nama Pelanggan:</td>
                                    <td style="padding: 10px 5px; border-bottom: 1px solid #f9f9f9;"><strong><?php echo htmlspecialchars($data['nama_lengkap']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 5px; color: #555; border-bottom: 1px solid #f9f9f9;">No HP:</td>
                                    <td style="padding: 10px 5px; border-bottom: 1px solid #f9f9f9;"><strong><?php echo htmlspecialchars($data['no_hp']); ?></strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 5px; color: #555;">Alamat:</td>
                                    <td style="padding: 10px 5px;"><strong><?php echo htmlspecialchars($data['alamat_lengkap']); ?></strong></td>
                                </tr>
                            </table>
                        </div>

                        <div style="flex: 1; padding: 20px;">
                            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Data Ukuran</h3>
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 8px 5px; color: #555; border-bottom: 1px solid #f9f9f9;">Lingkar Dada:</td>
                                    <td style="padding: 8px 5px; border-bottom: 1px solid #f9f9f9;"><strong><?php echo $data['lingkar_dada'] ?? '-'; ?> cm</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 5px; color: #555; border-bottom: 1px solid #f9f9f9;">Lebar Bahu:</td>
                                    <td style="padding: 8px 5px; border-bottom: 1px solid #f9f9f9;"><strong><?php echo $data['lebar_bahu'] ?? '-'; ?> cm</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 5px; color: #555; border-bottom: 1px solid #f9f9f9;">Lingkar Pinggang:</td>
                                    <td style="padding: 8px 5px; border-bottom: 1px solid #f9f9f9;"><strong><?php echo $data['lingkar_pinggang'] ?? '-'; ?> cm</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 5px; color: #555;">Panjang:</td>
                                    <td><strong><?php echo $data['panjang_baju'] ?? '-'; ?> cm</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div style="display: flex; border-top: 1px solid #ccc; background: #fafafa; padding: 15px 0;">
                        <div style="flex: 1; text-align: center; border-right: 1px solid #ccc;">
                            <a href="edit_data_pelanggan.php?id=<?php echo $id_pelanggan; ?>" style="background-color: #1d7a6c; color: white; padding: 10px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; width: 80%;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Data Pelanggan
                            </a>
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <a href="edit_ukuran.php?id=<?php echo $id_pelanggan; ?>" style="background-color: #1d7a6c; color: white; padding: 10px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; width: 80%;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Ukuran
                            </a>
                        </div>
                    </div>
                </div>

                <div style="background: white; border: 1px solid #ccc; border-radius: 4px; margin-top: 20px; padding: 20px;">
                    <h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px;">Riwayat Pesanan</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #eee;">
                                <th style="padding: 12px; border: 1px solid #ddd;">Tanggal Masuk</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">Jenis Pesanan</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">Total Biaya</th>
                                <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil data dari tabel pesanan yang berhubungan dengan id_pelanggan ini
                            $query_pesanan = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pelanggan = '$id_pelanggan' ORDER BY tgl_masuk DESC");
                            if (mysqli_num_rows($query_pesanan) > 0) {
                                while($pesanan = mysqli_fetch_assoc($query_pesanan)) {
                                    echo "<tr>";
                                    echo "<td style='padding: 12px; border: 1px solid #ddd;'>".date('d F Y', strtotime($pesanan['tgl_masuk']))."</td>";
                                    echo "<td style='padding: 12px; border: 1px solid #ddd;'>".$pesanan['jenis_pesanan']."</td>";
                                    echo "<td style='padding: 12px; border: 1px solid #ddd;'>Rp ".number_format($pesanan['total_biaya'], 0, ',', '.')."</td>";
                                    echo "<td style='padding: 12px; border: 1px solid #ddd; text-align: center;'><span style='background: #2D5E55; color: white; padding: 4px 10px; border-radius: 12px; font-size: 12px;'>".$pesanan['status_produksi']."</span></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' style='padding: 12px; text-align: center;'>Belum ada riwayat pesanan</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div> 

    <script>
    function fungsiCari() {
        var input = document.getElementById("inputCari");
        var filter = input.value.toUpperCase();
        var rows = document.getElementsByClassName("baris-pelanggan");

        for (var i = 0; i < rows.length; i++) {
            var namaElemen = rows[i].getElementsByClassName("nama-pelanggan")[0];
            if (namaElemen) {
                var textValue = namaElemen.textContent || namaElemen.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
    </script>
    
</body>
</html>