<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: pembayaran.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$sql = "SELECT pembayaran.*, pelanggan.nama_lengkap, pesanan.total_biaya 
        FROM pembayaran 
        JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan 
        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
        WHERE pembayaran.id_pembayaran = '$id'";
$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($query);

$total_biaya = $data['total_biaya'];
$sudah_bayar_lama = $data['uang_muka'];
$sisa_tagihan = $total_biaya - $sudah_bayar_lama;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .readonly-input { background-color: #f0f0f0; cursor: not-allowed; border: 1px solid #ccc; padding: 10px; width: 100%; border-radius: 4px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .sisa-text { color: red; font-size: 0.9rem; margin-top: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="header-breadcrumb">
            <p><a href="pembayaran.php"><i class="fa-solid fa-chevron-left"></i> Kembali</a> / Edit Pembayaran</p>
        </div>

        <div class="form-section">
            <h2 class="form-title" style="text-align: center;">Edit Pembayaran: <?= $data['nama_lengkap']; ?></h2>
            
            <div class="form-container" style="background: #fff; padding: 30px; border-radius: 8px; max-width: 900px; margin: auto;">
                <form action="proses-edit-bayar.php" method="POST">
                    <input type="hidden" name="id_pembayaran" value="<?= $data['id_pembayaran']; ?>">
                    <input type="hidden" name="sudah_bayar_lama" value="<?= $sudah_bayar_lama; ?>">
                    <input type="hidden" name="total_biaya" value="<?= $total_biaya; ?>">

                    <div class="form-grid">
                        <div class="input-group">
                            <label>Total Biaya Pesanan</label>
                            <input type="text" value="Rp <?= number_format($total_biaya, 0, ',', '.'); ?>" class="readonly-input" readonly>
                        </div>

                        <div class="input-group">
                            <label>Status Pembayaran</label>
                            <select name="status_bayar" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
                                <option value="DP" <?= $data['status_bayar'] == 'DP' ? 'selected' : ''; ?>>DP</option>
                                <option value="Lunas" <?= $data['status_bayar'] == 'Lunas' ? 'selected' : ''; ?>>Lunas</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label>Uang yang Sudah Masuk</label>
                            <input type="text" value="Rp <?= number_format($sudah_bayar_lama, 0, ',', '.'); ?>" class="readonly-input" readonly>
                            <div class="sisa-text">Sisa Tagihan: Rp <?= number_format($sisa_tagihan, 0, ',', '.'); ?></div>
                        </div>

                        <div class="input-group">
                            <label>Input Sisa Pembayaran (Rp)</label>
                            <input type="number" name="tambahan_bayar" style="width: 100%; padding: 10px; border-radius: 4px; border: 1px solid #ccc;" required min="0" max="<?= $sisa_tagihan; ?>">
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 30px;">
                        <button type="submit" name="update" class="btn-save" style="background-color: #2D5E55; color: white; padding: 12px 40px; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>