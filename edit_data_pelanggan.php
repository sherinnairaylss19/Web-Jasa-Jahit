<?php
session_start();
include 'koneksi.php';

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id_pelanggan = $_GET['id'];

// Ambil data pelanggan berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pelanggan.php';</script>";
    exit;
}

// Proses Update Data jika tombol simpan ditekan
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat_lengkap']);

    $update = mysqli_query($koneksi, "UPDATE pelanggan SET 
                nama_lengkap = '$nama', 
                no_hp = '$no_hp', 
                alamat_lengkap = '$alamat' 
                WHERE id_pelanggan = '$id_pelanggan'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='detail_pelanggan.php?id=$id_pelanggan';</script>";
    } else {
        echo "<script>alert('Gagal update data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pelanggan</title>
    <link rel="stylesheet" href="css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container" style="display: flex; min-height: 100vh; width: 100%; margin: 0; padding: 0;">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
        </nav>

        <div class="main-content">
            <div class="top-bar">
                <a href="detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>" style="text-decoration: none; color: white; font-size: 14px;">
                    <i class="fa-solid fa-chevron-left"></i> Kembali ke Detail / <strong>Edit Data Pelanggan</strong>
                </a>
            </div>

            <div class="dashboard-body" style="padding: 20px;">
                <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #ddd; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; margin-bottom: 25px; text-align: center; color: #2D5E55;">Edit Profil Pelanggan</h3>
                    
                    <form action="" method="POST">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Nama Pelanggan</label>
                            <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">No HP</label>
                            <input type="text" name="no_hp" value="<?php echo htmlspecialchars($data['no_hp']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 25px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Alamat</label>
                            <textarea name="alamat_lengkap" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; height: 100px; box-sizing: border-box;"><?php echo htmlspecialchars($data['alamat_lengkap']); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 10px;">
                            <button type="button" onclick="window.location='detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>'" style="flex: 1; padding: 12px; background: #eee; border: 1px solid #ccc; cursor: pointer; border-radius: 6px; font-weight: bold;">Batal</button>
                            <button type="submit" name="submit" style="flex: 1; padding: 12px; background: #2D5E55; color: white; border: none; cursor: pointer; border-radius: 6px; font-weight: bold;">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>