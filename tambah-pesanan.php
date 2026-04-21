<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

if (isset($_POST['submit'])) {
    $nama    = $_POST['nama'];        
    $no_hp   = $_POST['no_hp'];        
    $alamat  = $_POST['alamat'];        
    $tgl_m   = $_POST['tgl_masuk'];
    $tgl_t   = $_POST['tgl_tenggat'];
    $total   = $_POST['total'];        
    $jenis   = $_POST['jenis_pesanan'];
    $catatan = $_POST['catatan'];
    $status  = "Proses";

    // AMBIL DATA UKURAN BARU
    $l_bahu   = $_POST['lebar_bahu'];
    $l_dada   = $_POST['lingkar_dada'];
    $p_lengan = $_POST['panjang_lengan'];
    $p_baju   = $_POST['panjang_baju'];

    $query_pelanggan = "INSERT INTO pelanggan (nama_lengkap, no_hp, alamat_lengkap) 
                        VALUES ('$nama', '$no_hp', '$alamat')";
    
    if (mysqli_query($koneksi, $query_pelanggan)) {
        $id_pelanggan = mysqli_insert_id($koneksi);

        // UPDATE QUERY INSERT PESANAN (Tambahkan kolom ukuran)
        $query_pesanan = "INSERT INTO pesanan (id_pelanggan, tgl_masuk, tgl_tenggat, total_biaya, jenis_pesanan, catatan, status_produksi, lebar_bahu, lingkar_dada, panjang_lengan, panjang_baju) 
                          VALUES ('$id_pelanggan', '$tgl_m', '$tgl_t', '$total', '$jenis', '$catatan', '$status', '$l_bahu', '$l_dada', '$p_lengan', '$p_baju')";

        if (mysqli_query($koneksi, $query_pesanan)) {
            echo "<script>alert('Data Berhasil Disimpan'); window.location='pesanan.php';</script>";
        } else {
            echo "Error Pesanan: " . mysqli_error($koneksi);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Tambahkan Pesanan</p>
            </div>

            <div class="form-section">
                <h2 class="form-title">Form Pesanan Baru</h2>
                
                <form action="" method="POST">
                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>
                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" required>
                            </div>
                            <div class="input-group">
                                <label>No Handphone</label>
                                <input type="text" name="no_hp" required>
                            </div>
                            <div class="input-group">
                                <label>Alamat Lengkap</label>
                                <input type="text" name="alamat" required>
                            </div>
                            <div class="input-row">
                                <div class="input-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" required>
                                </div>
                                <div class="input-group">
                                    <label>Tanggal Tenggat</label>
                                    <input type="date" name="tgl_tenggat" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Total Biaya</label>
                                <input type="number" name="total" required>
                            </div>
                        </div>

                        <div class="form-column">
                            <h3>Detail Jahitan & Ukuran</h3>
                            <div class="input-group">
                                <label>Jenis Pesanan:</label>
                                <input type="text" name="jenis_pesanan">
                            </div>
                        <div class="input-row">
                        <div class="input-group">
                            <label>Lebar Bahu</label>
                            <input type="text" name="lebar_bahu" placeholder="cm">
                        </div>
                        <div class="input-group">
                            <label>Lingkar Dada</label>
                            <input type="text" name="lingkar_dada" placeholder="cm">
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group">
                            <label>Panjang Lengan</label>
                            <input type="text" name="panjang_lengan" placeholder="cm">
                        </div>
                        <div class="input-group">
                            <label>Panjang Baju/Celana</label>
                            <input type="text" name="panjang_baju" placeholder="cm">
                        </div>
                    </div>
                            <div class="input-group">
                                <label>Catatan / Ukuran:</label>
                                <textarea name="catatan" rows="6"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="submit" class="btn-save">Simpan Pesanan</button>
                        <button type="button" class="btn-cancel" onclick="window.history.back()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="js/tambah-pesanan.js"></script>
</body>
</html>