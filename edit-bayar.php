<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "toko-jahit");

if (!isset($_GET['id'])) {
    header("Location: pembayaran.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT pembayaran.*, pelanggan.nama_lengkap, pesanan.total_biaya 
        FROM pembayaran 
        JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan 
        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
        WHERE pembayaran.id_pembayaran = '$id'";
$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pembayaran.php"><i class="fa-solid fa-chevron-left"></i> Kembali</a> / Edit Pembayaran</p>
            </div>

            <div class="form-section">
                <h2 class="form-title">Edit Pembayaran: <?= $data['nama_lengkap']; ?></h2>
                <form action="proses-edit-bayar.php" method="POST">
                    <input type="hidden" name="id_pembayaran" value="<?= $data['id_pembayaran']; ?>">
                    <div class="form-grid">
                        <div class="form-column">
                            <div class="input-group">
                                <label>Total Biaya Pesanan</label>
                                <input type="text" value="Rp <?= number_format($data['total_biaya'],0,',','.'); ?>" disabled style="background: #eee;">
                            </div>
                            <div class="input-group">
                                <label>Uang yang Sudah Dibayar</label>
                                <input type="number" name="uang_muka" value="<?= $data['uang_muka']; ?>" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="input-group">
                                <label>Status</label>
                                <select name="status_bayar" class="status-select">
                                    <option value="DP" <?= $data['status_bayar'] == 'DP' ? 'selected' : ''; ?>>DP</option>
                                    <option value="Lunas" <?= $data['status_bayar'] == 'Lunas' ? 'selected' : ''; ?>>LUNAS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-buttons" style="margin-top: 20px;">
                        <button type="submit" name="update" class="btn-save">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>