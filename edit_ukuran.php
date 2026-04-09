<?php
session_start();
include 'koneksi.php';

// Pastikan ada ID pelanggan yang dikirim melalui URL
if (!isset($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id_pelanggan = $_GET['id'];

// Ambil data ukuran berdasarkan id_pelanggan
// Catatan: Pastikan tabel 'pelanggan' memiliki kolom-kolom ini atau sesuaikan join-nya
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pelanggan.php';</script>";
    exit;
}

// Proses Update ketika tombol ditekan
if (isset($_POST['update_ukuran'])) {
    $ld = mysqli_real_escape_string($koneksi, $_POST['lingkar_dada']);
    $lb = mysqli_real_escape_string($koneksi, $_POST['lebar_bahu']);
    $lp = mysqli_real_escape_string($koneksi, $_POST['lingkar_pinggang']);
    $pj = mysqli_real_escape_string($koneksi, $_POST['panjang']);
    $jp = mysqli_real_escape_string($koneksi, $_POST['jenis_pesanan']);

    // Query Update (Sesuaikan nama kolom dengan database Anda)
    $sql_update = "UPDATE pelanggan SET 
                    lingkar_dada = '$ld', 
                    lebar_bahu = '$lb', 
                    lingkar_pinggang = '$lp', 
                    panjang = '$pj',
                    jenis_pesanan = '$jp'
                  WHERE id_pelanggan = '$id_pelanggan'";

    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>alert('Ukuran berhasil diperbarui!'); window.location='detail_pelanggan.php?id=$id_pelanggan';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ukuran Pelanggan</title>
    <link rel="stylesheet" href="css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container"  style="display: flex; min-height: 100vh; width: 100%; margin: 0; padding: 0;">
        <nav class="sidebar">
            <div class="profile">
                <img src="<?php echo $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
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
                <a href="detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>" style="text-decoration: none; color: white; font-size: 14px;">
                    <i class="fa-solid fa-chevron-left"></i> Kembali ke Detail / <strong>Edit Data Ukuran</strong>
                </a>
            </div>

            <div class="dashboard-body" style="padding: 20px;">
                <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #ddd; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <h3 style="margin-top: 0; margin-bottom: 25px; text-align: center; color: #2D5E55;">Update Ukuran & Jahitan: <?php echo htmlspecialchars($data['nama_lengkap']); ?></h3>
                    
                    <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Lingkar Dada (cm)</label>
                            <input type="number" name="lingkar_dada" value="<?php echo $data['lingkar_dada']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Lebar Bahu (cm)</label>
                            <input type="number" name="lebar_bahu" value="<?php echo $data['lebar_bahu']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Lingkar Pinggang (cm)</label>
                            <input type="number" name="lingkar_pinggang" value="<?php echo $data['lingkar_pinggang']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Panjang (cm)</label>
                            <input type="number" name="panjang" value="<?php echo $data['panjang']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Jenis Pesanan</label>
                            <input type="text" name="jenis_pesanan" value="<?php echo htmlspecialchars($data['jenis_pesanan']); ?>" placeholder="Contoh: Permak Celana, Jahit Kebaya" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div style="grid-column: span 2; display: flex; gap: 15px; margin-top: 15px;">
                            <button type="button" onclick="window.location='detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>'" style="flex: 1; padding: 12px; background: #f1f1f1; color: #333; border: 1px solid #ccc; cursor: pointer; border-radius: 6px; font-weight: bold;">Batal</button>
                            <button type="submit" name="update_ukuran" style="flex: 1; padding: 12px; background: #2D5E55; color: white; border: none; cursor: pointer; border-radius: 6px; font-weight: bold;">Update Ukuran</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>