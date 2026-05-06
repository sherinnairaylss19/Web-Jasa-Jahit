<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: index.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: pesanan.php"); exit(); }

$id_pesanan = $_GET['id'];
$query_get = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.no_hp, pelanggan.alamat_lengkap 
                                     FROM pesanan 
                                     JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                     WHERE pesanan.id_pesanan = '$id_pesanan'");
$data = mysqli_fetch_assoc($query_get);

if (isset($_POST['update'])) {
    $nama = $_POST['nama']; $no_hp = $_POST['no_hp']; $alamat = $_POST['alamat'];
    $tgl_m = $_POST['tgl_masuk']; $tgl_t = $_POST['tgl_tenggat']; $total = $_POST['total'];
    $jenis = $_POST['jenis_pesanan']; $catatan = $_POST['catatan']; $status = $_POST['status_produksi'];
    $l_bahu = $_POST['lebar_bahu']; $l_dada = $_POST['lingkar_dada'];
    $p_lengan = $_POST['panjang_lengan']; $p_baju = $_POST['panjang_baju'];
    
    mysqli_query($koneksi, "UPDATE pelanggan SET nama_lengkap = '$nama', no_hp = '$no_hp', alamat_lengkap = '$alamat' WHERE id_pelanggan = '".$data['id_pelanggan']."'");
    
    $sql_update = "UPDATE pesanan SET tgl_masuk='$tgl_m', tgl_tenggat='$tgl_t', total_biaya='$total', jenis_pesanan='$jenis', 
                   catatan='$catatan', status_produksi='$status', lebar_bahu='$l_bahu', lingkar_dada='$l_dada', 
                   panjang_lengan='$p_lengan', panjang_baju='$p_baju' WHERE id_pesanan = '$id_pesanan'";
                   
    if (mysqli_query($koneksi, $sql_update)) {
        echo "<script>alert('Berhasil!'); window.location='pesanan.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar"><?php include 'sidebar.php'; ?></nav>
        <div class="main-content">
            <div class="header-breadcrumb"><p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Edit Pesanan</p></div>
            <div class="form-section">
                <h2 class="form-title">Edit Data Pesanan</h2>
                <form action="" method="POST">
                    <div class="form-grid">
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>
                            <div class="input-group"><label>Nama Lengkap</label><input type="text" name="nama" value="<?= $data['nama_lengkap']; ?>" required></div>
                            <div class="input-group"><label>No Handphone</label><input type="text" name="no_hp" value="<?= $data['no_hp']; ?>" required></div>
                            <div class="input-group"><label>Alamat Lengkap</label><input type="text" name="alamat" value="<?= $data['alamat_lengkap']; ?>" required></div>
                            <div class="input-row">
                                <div class="input-group"><label>Tanggal Masuk</label><input type="date" name="tgl_masuk" value="<?= $data['tgl_masuk']; ?>" required></div>
                                <div class="input-group"><label>Tanggal Tenggat</label><input type="date" name="tgl_tenggat" value="<?= $data['tgl_tenggat']; ?>" required></div>
                            </div>
                            <div class="input-group"><label>Total Biaya</label><input type="number" name="total" value="<?= $data['total_biaya']; ?>" required></div>
                            <div class="input-group"><label>Status Produksi</label>
                                <select name="status_produksi" style="width:100%; padding:8px;">
                                    <option value="Proses" <?= ($data['status_produksi']=='Proses')?'selected':''; ?>>Proses</option>
                                    <option value="Selesai" <?= ($data['status_produksi']=='Selesai')?'selected':''; ?>>Selesai</option>
                                    <option value="Telat" <?= ($data['status_produksi']=='Telat')?'selected':''; ?>>Telat</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-column">
                            <h3>Detail Jahitan & Ukuran</h3>
                            <div class="input-group"><label>Jenis Pesanan:</label><input type="text" name="jenis_pesanan" value="<?= $data['jenis_pesanan']; ?>"></div>
                            <div class="input-row">
                                <div class="input-group"><label>Lebar Bahu</label><input type="text" name="lebar_bahu" value="<?= $data['lebar_bahu']; ?>" placeholder="cm"></div>
                                <div class="input-group"><label>Lingkar Dada</label><input type="text" name="lingkar_dada" value="<?= $data['lingkar_dada']; ?>" placeholder="cm"></div>
                            </div>
                            <div class="input-row">
                                <div class="input-group"><label>Panjang Lengan</label><input type="text" name="panjang_lengan" value="<?= $data['panjang_lengan']; ?>" placeholder="cm"></div>
                                <div class="input-group"><label>Panjang Baju/Celana</label><input type="text" name="panjang_baju" value="<?= $data['panjang_baju']; ?>" placeholder="cm"></div>
                            </div>
                            <div class="input-group"><label>Catatan Tambahan:</label><textarea name="catatan" rows="6"><?= $data['catatan']; ?></textarea></div>
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

                    </div>                </form>
            </div>
        </div>
    </div>
</body>
</html>