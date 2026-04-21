<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$id_pesanan = $_GET['id'];

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
<style>
    .btn-delete {
    background-color: #e74c3c; 
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 0 5px;
    transition: background 0.3s;
    border: none;
    cursor: pointer;
}

.btn-delete:hover {
    background-color: #c0392b;
}
</style>

<body>
    <div class="container">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
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
                        <a href="hapus_pesanan.php?id=<?= $id_pesanan; ?>" 
                            class="btn-delete" 
                            onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Semua data pesanan ini akan hilang.')">
                            <i class="fa-solid fa-trash"></i>Hapus Pesanan
                        </a>
                        <button type="button" class="btn-cancel" onclick="window.location='pesanan.php'">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>