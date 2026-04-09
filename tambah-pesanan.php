<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

// HANYA jalankan ini jika tombol submit diklik
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

    $query_pelanggan = "INSERT INTO pelanggan (nama_lengkap, no_hp, alamat_lengkap) 
                        VALUES ('$nama', '$no_hp', '$alamat')";
    
    if (mysqli_query($koneksi, $query_pelanggan)) {

        $id_pelanggan = mysqli_insert_id($koneksi);

        $query_pesanan = "INSERT INTO pesanan (id_pelanggan, tgl_masuk, tgl_tenggat, total_biaya, jenis_pesanan, catatan, status_produksi) 
                          VALUES ('$id_pelanggan', '$tgl_m', '$tgl_t', '$total', '$jenis', '$catatan', '$status')";

        if (mysqli_query($koneksi, $query_pesanan)) {
            echo "<script>alert('Data Berhasil Disimpan'); window.location='pesanan.php';</script>";
        } else {
            echo "Error Pesanan: " . mysqli_error($koneksi);
        }
    } else {
        echo "Error Pelanggan: " . mysqli_error($koneksi);
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
            <div class="profile">
                <img src="<?php echo $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
                <span id="user-nama"><?php echo $_SESSION['nama']; ?></span>
            </div>
            <ul>
                <li><a href="dashboard_penjahit.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-desktop"></i> Dashboard</a></li>
                <li class="active"><i class="fa-solid fa-pen-to-square"></i> Pesanan</li>
                <li><a href="pelanggan.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-user-group"></i> Pelanggan</a></li>
                <li><a href="pembayaran.php" style="color:white; text-decoration:none;"><i class="fa-solid fa-wallet"></i> Pembayaran</a></li>
                <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i> Logout</a></li>
            </ul>
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