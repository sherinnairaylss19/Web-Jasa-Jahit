<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "toko-jahit");

$sql = "SELECT 
            pembayaran.*, 
            pelanggan.nama_lengkap, 
            pesanan.tgl_masuk,
            pesanan.total_biaya
        FROM pembayaran
        INNER JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan
        INNER JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan";

$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="css/pembayaran.css">
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
                <li><a href="dashboard_penjahit.php"><i class="fa-solid fa-desktop"></i> Dashboard</a></li>
                <li><a href="pesanan.php"><i class="fa-solid fa-pen-to-square"></i> Pesanan</a></li>
                <li><a href="pelanggan.php"><i class="fa-solid fa-user-group"></i> Pelanggan</a></li>
                <li class="active"><a href="pembayaran.php"><i class="fa-solid fa-wallet"></i> Pembayaran</a></li>
                <li><a href="logout.php"><i class="fa-solid fa-power-off"></i> Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header class="top-bar">
                <h1 class="top-bar-title">Pembayaran</h1>
            </header>

            <div class="content-body">
                <div class="toolbar">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Cari Nama Pelanggan">
                    </div>
                    <a href="tambah-pembayaran.php" class="btn-add">+ Tambahkan Pembayaran</a>
                </div>

                <div class="table-section">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Uang Muka</th>
                                <th>Sisa Bayar</th>
                                <th>Status Bayar</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($query)) : 
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= $row['nama_lengkap']; ?></strong></td>
                                <td><?= $row['tgl_masuk']; ?></td>
                                <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['uang_muka'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['sisa_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $status = strtolower($row['status_bayar']);
                                    $class_status = ($status == 'lunas') ? 'selesai' : 'proses';
                                    ?>
                                    <span class="status <?= $class_status; ?>"><?= strtoupper($status); ?></span>
                                </td>
                                <td class="aksi-buttons">
                                    <div class="button-group">
                                        <a href="edit-bayar.php?id=<?= $row['id_pembayaran']; ?>" class="btn-edit-bayar">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>