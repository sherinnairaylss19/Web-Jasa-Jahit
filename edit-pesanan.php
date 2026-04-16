<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

// Ambil ID dari URL (Pastikan di pesanan.php linknya adalah edit-pesanan.php?id=...)
if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$id_pesanan = $_GET['id'];

// Ambil data lama untuk ditampilkan di form
$query_get = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.no_hp 
                                     FROM pesanan 
                                     JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                     WHERE pesanan.id_pesanan = '$id_pesanan'");
$data = mysqli_fetch_assoc($query_get);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pesanan.php';</script>";
    exit();
}

// Proses Update Data
if (isset($_POST['update'])) {
    $nama    = $_POST['nama'];
    $no_hp   = $_POST['no_hp'];
    $status  = $_POST['status_produksi'];
    $total   = $_POST['total'];
    $jenis   = $_POST['jenis_pesanan'];
    $catatan = $_POST['catatan'];
    $id_plg  = $data['id_pelanggan'];

    // Update tabel pelanggan
    $update_pelanggan = "UPDATE pelanggan SET nama_lengkap = '$nama', no_hp = '$no_hp' WHERE id_pelanggan = '$id_plg'";
    
    // Update tabel pesanan
    $update_pesanan = "UPDATE pesanan SET 
                        status_produksi = '$status', 
                        total_biaya = '$total', 
                        jenis_pesanan = '$jenis', 
                        catatan = '$catatan' 
                       WHERE id_pesanan = '$id_pesanan'";

    if (mysqli_query($koneksi, $update_pelanggan) && mysqli_query($koneksi, $update_pesanan)) {
        echo "<script>alert('Perubahan Berhasil Disimpan'); window.location='pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <div class="profile">
                <img src="<?php echo $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
                <span id="user-nama"><?php echo $_SESSION['nama']; ?></span>
            </div>
            <ul>
                <li><a href="dashboard_penjahit.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-desktop"></i> Dashboard</a></li>
                <li class="active"><a href="pesanan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-pen-to-square"></i> Pesanan</a></li>
                <li><a href="pelanggan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-user-group"></i> Pelanggan</a></li>
                <li><a href="pembayaran.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-wallet"></i> Pembayaran</a></li>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i> Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Edit Pesanan</p>
            </div>

            <div class="form-section">
                <h2 class="form-title">Edit Data Pesanan</h2>
                
                <form action="" method="POST">
                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>
                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" value="<?= $data['nama_lengkap']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label>No Handphone</label>
                                <input type="text" name="no_hp" value="<?= $data['no_hp']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Status Produksi</label>
                                <select name="status_produksi" class="status-select">
                                    <option value="Proses" <?= ($data['status_produksi'] == 'Proses') ? 'selected' : ''; ?>>Proses</option>
                                    <option value="Selesai" <?= ($data['status_produksi'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                    <option value="Telat" <?= ($data['status_produksi'] == 'Telat') ? 'selected' : ''; ?>>Telat</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Total Biaya</label>
                                <input type="number" name="total" value="<?= $data['total_biaya']; ?>" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3>Detail Jahitan</h3>
                            <div class="input-group">
                                <label>Jenis Pesanan</label>
                                <input type="text" name="jenis_pesanan" value="<?= $data['jenis_pesanan']; ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="catatan" rows="5"><?= $data['catatan']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
                        <button type="button" class="btn-cancel" onclick="window.location='pesanan.php'">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>