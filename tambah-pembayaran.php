<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$id_pesanan_url = isset($_GET['id']) ? $_GET['id'] : '';

$nama_pelanggan = "";
$total_biaya = 0;

if ($id_pesanan_url) {
    $id_pesanan_url = mysqli_real_escape_string($koneksi, $id_pesanan_url);
    $query_info = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap 
                                          FROM pesanan 
                                          JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                          WHERE id_pesanan = '$id_pesanan_url'");
    $data = mysqli_fetch_assoc($query_info);
    if ($data) {
        $nama_pelanggan = $data['nama_lengkap'];
        $total_biaya = $data['total_biaya'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        
        .form-container { background: #fff; padding: 20px; border-radius: 8px; max-width: 800px; margin: auto; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input, .input-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .readonly-input { background-color: #f0f0f0; cursor: not-allowed; }
        .btn-save { background-color: #2D5E55; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="header-breadcrumb">
            <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali ke Pesanan</a> / Tambahkan Pembayaran</p>
        </div>

        <div class="form-section">
            <h2 class="form-title" style="text-align: center;">Form Pembayaran</h2>
            
            <div class="form-container">
                <form action="proses-tambah-pembayaran.php" method="POST">
        
                    <input type="hidden" name="id_pesanan" value="<?= $id_pesanan_url; ?>">

                    <div class="input-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" value="<?= $nama_pelanggan; ?>" class="readonly-input" readonly>
                        <small>Total Tagihan: <strong>Rp <?= number_format($total_biaya, 0, ',', '.'); ?></strong></small>
                    </div>

                    <div class="input-group">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" name="tgl_pembayaran" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="input-group">
                        <label>Nominal Bayar (Rp)</label>
                        <input type="number" name="uang_muka" required>
                    </div>

                    <div class="input-group">
                        <label>Status Pembayaran</label>
                        <select name="status_bayar" required>
                            <option value="DP">Uang Muka (DP)</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>

                    <div class="form-buttons" style="margin-top: 20px;">
                        <button type="submit" name="simpan" class="btn-save">Simpan Pembayaran</button>
                        <a href="pesanan.php" class="btn-cancel" style="text-decoration: none; margin-left: 10px; color: #666;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>