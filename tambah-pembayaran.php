<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$query_pesanan = mysqli_query($koneksi, "SELECT pesanan.id_pesanan, pelanggan.nama_lengkap, pesanan.total_biaya 
                                        FROM pesanan 
                                        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pembayaran - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
   

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pembayaran.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pembayaran</a> / Tambahkan Pembayaran</p>
            </div>

            <div class="form-section">
                <h2 class="form-title">Form Pembayaran Baru</h2>
                
                <form action="proses-tambah-pembayaran.php" method="POST">
                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Detail Pembayaran</h3>
                            <div class="input-group">
                                <label>Pilih Pesanan (Pelanggan)</label>
                                <select name="id_pesanan" id="id_pesanan" class="status-select" required>
                                    <option value="">-- Pilih Pesanan --</option>
                                    <?php while($row = mysqli_fetch_assoc($query_pesanan)) : ?>
                                        <option value="<?= $row['id_pesanan']; ?>">
                                            <?= $row['nama_lengkap']; ?> (Total: Rp <?= number_format($row['total_biaya'],0,',','.'); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Tanggal Pembayaran</label>
                                <input type="date" name="tgl_pembayaran" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3>Nominal & Status</h3>
                            <div class="input-group">
                                <label>Uang Muka (DP) / Bayar</label>
                                <input type="number" name="uang_muka" placeholder="Masukkan jumlah bayar" required>
                            </div>
                            <div class="input-group">
                                <label>Status Pembayaran</label>
                                <select name="status_bayar" class="status-select" required>
                                    <option value="DP">DP (Uang Muka)</option>
                                    <option value="Lunas">LUNAS</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="simpan" class="btn-save">Simpan Pembayaran</button>
                        <button type="button" class="btn-cancel" onclick="window.history.back()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>