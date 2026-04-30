<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: pelanggan.php");
    exit;
}

$id_pelanggan = $_GET['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='pelanggan.php';</script>";
    exit;
}

// Proses Update ketika tombol ditekan
if (isset($_POST['update_ukuran'])) {
    $ld = mysqli_real_escape_string($koneksi, $_POST['lingkar_dada']);
    $lb = mysqli_real_escape_string($koneksi, $_POST['lebar_bahu']);
    $pl = mysqli_real_escape_string($koneksi, $_POST['panjang_lengan']);
    $pj = mysqli_real_escape_string($koneksi, $_POST['panjang_baju']);


    
$sql_update_pelanggan = "UPDATE pelanggan SET 
                        lingkar_dada = '$ld', 
                        lebar_bahu = '$lb', 
                        panjang_lengan = '$pl', 
                        panjang_baju = '$pj'
                      WHERE id_pelanggan = '$id_pelanggan'";

$sql_update_pesanan = "UPDATE pesanan SET 
                        lingkar_dada = '$ld', 
                        lebar_bahu = '$lb', 
                        panjang_lengan = '$pl', 
                        panjang_baju = '$pj'
                      WHERE id_pelanggan = '$id_pelanggan' 
                      ORDER BY id_pesanan DESC LIMIT 1";

mysqli_query($koneksi, $sql_update_pelanggan);
mysqli_query($koneksi, $sql_update_pesanan);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ukuran Pelanggan</title>
    <link rel="stylesheet" href="css/pelanggan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container"  style="display: flex; min-height: 100vh; width: 100%; margin: 0; padding: 0;">
        <nav class="sidebar">
    <?php include 'sidebar.php'; ?>
</nav>

        <div class="main-content">
            <div class="top-bar">
                <a href="detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>" style="text-decoration: none; color: white; font-size: 14px;">
                    <i class="fa-solid fa-chevron-left"></i> Kembali ke Detail / <strong>Edit Data Ukuran</strong>
                </a>
            </div>

            <div class="dashboard-body" style="padding: 20px;">
                <div style="background: white; padding: 30px; border-radius: 10px; border: 1px solid #ddd; max-width: 700px; margin: 0 auto; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <h3 style="margin-top: 0; margin-bottom: 25px; text-align: center; color: #2D5E55;">Update Ukuran : <?php echo htmlspecialchars($data['nama_lengkap']); ?></h3>
                    
                    <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Lingkar Dada/Pinggang (cm)</label>
                            <input type="number" name="lingkar_dada" value="<?php echo $data['lingkar_dada']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Lebar Bahu (cm)</label>
                            <input type="number" name="lebar_bahu" value="<?php echo $data['lebar_bahu']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Panjang Lengan (cm)</label>
                            <input type="number" name="panjang_lengan" value="<?php echo $data['panjang_lengan']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: bold;">Panjang Baju/Celana (cm)</label>
                            <input type="number" name="panjang_baju" value="<?php echo $data['panjang_baju']; ?>" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                        </div>


                        <div style="grid-column: span 2; display: flex; gap: 15px; margin-top: 15px;">
                            <button type="button" onclick="window.location='detail_pelanggan.php?id=<?php echo $id_pelanggan; ?>'" style="flex: 1; padding: 12px; background: #f1f1f1; color: #333; border: 1px solid #ccc; cursor: pointer; border-radius: 6px; font-weight: bold;">Batal</button>
                            <button type="submit" name="update_ukuran" style="flex: 1; padding: 12px; background: #2D5E55; color: white; border: none; cursor: pointer; border-radius: 6px; font-weight: bold;">Update Ukuran</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>