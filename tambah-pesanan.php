<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$id_p_lama = isset($_GET['id_pelanggan']) ? $_GET['id_pelanggan'] : "";
$nama = ""; $no_hp = ""; $alamat = "";

if ($id_p_lama != "") {
    $cek_p = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_p_lama'");
    $data_p = mysqli_fetch_assoc($cek_p);
    if ($data_p) {
        $nama   = $data_p['nama_lengkap'];
        $no_hp  = $data_p['no_hp'];
        $alamat = $data_p['alamat_lengkap'];
    }
}

if (isset($_POST['submit'])) {
    $id_pelanggan = $_POST['id_pelanggan_hidden']; 
    $nama    = $_POST['nama'];        
    $no_hp   = $_POST['no_hp'];        
    $alamat  = $_POST['alamat'];        
    $tgl_m   = $_POST['tgl_masuk'];
    $tgl_t   = $_POST['tgl_tenggat'];
    $total   = $_POST['total'];        
    $jenis   = $_POST['jenis_pesanan'];
    $catatan = $_POST['catatan'];
    $status  = "Proses";

    $l_bahu   = $_POST['lebar_bahu'];
    $l_dada   = $_POST['lingkar_dada'];
    $p_lengan = $_POST['panjang_lengan'];
    $p_baju   = $_POST['panjang_baju'];

    
    if (empty($id_pelanggan)) {
        $query_pelanggan = "INSERT INTO pelanggan (nama_lengkap, no_hp, alamat_lengkap) 
                            VALUES ('$nama', '$no_hp', '$alamat')";
        mysqli_query($koneksi, $query_pelanggan);
        $id_pelanggan = mysqli_insert_id($koneksi);
    } else {
   
        mysqli_query($koneksi, "UPDATE pelanggan SET no_hp='$no_hp', alamat_lengkap='$alamat' WHERE id_pelanggan='$id_pelanggan'");
    }

    // SIMPAN KE TABEL PESANAN
    $query_pesanan = "INSERT INTO pesanan (id_pelanggan, tgl_masuk, tgl_tenggat, total_biaya, jenis_pesanan, catatan, status_produksi, lebar_bahu, lingkar_dada, panjang_lengan, panjang_baju) 
                      VALUES ('$id_pelanggan', '$tgl_m', '$tgl_t', '$total', '$jenis', '$catatan', '$status', '$l_bahu', '$l_dada', '$p_lengan', '$p_baju')";

    if (mysqli_query($koneksi, $query_pesanan)) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location='pesanan.php';</script>";
    } else {
        echo "Error Pesanan: " . mysqli_error($koneksi);
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
        <nav class="sidebar"><?php include 'sidebar.php'; ?></nav>

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Tambahkan Pesanan</p>
            </div>

            <div class="form-section">
                <h2 class="form-title"><?= ($id_p_lama != "") ? "Pesanan Ulang: $nama" : "Form Pesanan Baru" ?></h2>
                
                <form action="" method="POST">
                    <!-- Hidden input untuk ID Pelanggan -->
                    <input type="hidden" name="id_pelanggan_hidden" value="<?= $id_p_lama ?>">

                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>
                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <!-- Readonly jika pelanggan lama agar tidak mengubah ID secara tidak sengaja -->
                                <input type="text" name="nama" value="<?= $nama ?>" required <?= ($id_p_lama != "") ? "readonly style='background:#f0f0f0;'" : "" ?>>
                            </div>
                            <div class="input-group">
                                <label>No Handphone</label>
                                <input type="text" name="no_hp" value="<?= $no_hp ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Alamat Lengkap</label>
                                <input type="text" name="alamat" value="<?= $alamat ?>" required>
                            </div>
                            <div class="input-row">
                                <div class="input-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" value="<?= date('Y-m-d') ?>" required>
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
                                <input type="text" name="jenis_pesanan" >
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
                                <label>Catatan Tambahan:</label>
                                <textarea name="catatan" rows="6"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="submit" class="btn-save">Simpan Pesanan</button>
                        <button type="button" class="btn-cancel" onclick="window.location='pesanan.php'">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>