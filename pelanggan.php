<?php
session_start();
include 'koneksi.php'; 

$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelanggan");
$data_total = mysqli_fetch_assoc($query_total);

$query_baru = mysqli_query($koneksi, "SELECT COUNT(*) as baru FROM pelanggan WHERE MONTH(tgl_masuk) = MONTH(CURRENT_DATE()) AND YEAR(tgl_masuk) = YEAR(CURRENT_DATE())");
$data_baru = mysqli_fetch_assoc($query_baru);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Pelanggan</title>
    <link rel="stylesheet" href="css/pelanggan.css">
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
            <li>
                <a href="dashboard_pemilik.php" style="color:white; text-decoration:none;">
                    <i class="fa-solid fa-desktop"></i> Dashboard
                </a>
            </li>

            <li>
                <a href="pesanan.php" style="color:white; text-decoration:none;">
                    <i class="fa-solid fa-pen-to-square"></i> Pesanan
                </a>
            </li>

            <li class="active">
                <i class="fa-solid fa-user-group"></i> Pelanggan
            </li>

            <li>
                <a href="pembayaran.php" style="color:white; text-decoration:none;">
                    <i class="fas fa-wallet"></i> Pembayaran
                </a>
            </li>

            <li>
                <a href="laporan.php" style="color:white; text-decoration:none;">
                <i class="fas fa-clock"></i> Laporan
                </a>
            </li>
            <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i>Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1 class="top-bar-title">Pelanggan</h1>
        </div>
      <div class="dashboard-body">
            <div class="stats-container" style="display: flex; gap: 20px; margin-bottom: 25px;">
                <div class="card" style="background: white; padding: 20px; border-radius: 10px; flex: 1; border: 1px solid #ddd;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <i class="fas fa-users fa-2x" style="color: #2D5E55;"></i>
                        <div>
                            <p style="color:#666; margin:0;">Total Pelanggan</p>
                            <h2 style="margin:0;"><?php echo $data_total['total']; ?></h2>
                        </div>
                    </div>
                </div>
                <div class="card" style="background: white; padding: 20px; border-radius: 10px; flex: 1; border: 1px solid #ddd;">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <i class="fas fa-user-plus fa-2x" style="color: #2D5E55;"></i>
                        <div>
                            <p style="color:#666; margin:0;">Pelanggan Baru</p>
                            <h2 style="margin:0;"><?php echo $data_baru['baru']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toolbar" style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="search-box" style="background:white; padding:10px; border-radius:8px; border:1px solid #ddd; width: 60%; display: flex; align-items: center;">
                    <i class="fas fa-search" style="color:#999;"></i>
                    <input type="text" id="inputCari" onkeyup="fungsiCari()" placeholder="Cari Nama Pelanggan..." style="border:none; outline:none; width:100%; margin-left:10px;">
                </div>
                <a href="tambah-pesanan.php" class="btn-tambah" style="background:#2D5E55; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold;">
                    <i class="fas fa-plus"></i> Tambah Pesanan
                </a>
            </div>

            <div class="table-section" style="background: white; padding: 20px; border-radius: 10px; border: 1px solid #ddd; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #eee;">
                            <th style="padding: 15px; text-align: left;">Nama</th>
                            <th style="padding: 15px; text-align: left;">Alamat</th>
                            <th style="padding: 15px; text-align: left;">No HP</th>
                            <th style="padding: 15px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM pelanggan ORDER BY nama_lengkap ASC";
                        $result = mysqli_query($koneksi, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td style='padding: 15px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                echo "<td style='padding: 15px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['alamat_lengkap']) . "</td>";
                                echo "<td style='padding: 15px; border-bottom: 1px solid #eee;'>" . htmlspecialchars($row['no_hp']) . "</td>";
                                echo "<td style='padding: 15px; border-bottom: 1px solid #eee; text-align:center;'>
                                        <a href='detail_pelanggan.php?id=".$row['id_pelanggan']."' style='background:#2D5E55; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-size:12px;'>Detail</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='padding:20px; text-align:center;'>Data tidak ditemukan</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
    </div>
</div>

<script>
function fungsiCari() {
    var input = document.getElementById("inputCari");
    var filter = input.value.toUpperCase();
    var table = document.querySelector("table");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdNama = tr[i].getElementsByTagName("td")[0];
        if (tdNama) {
            var textValue = tdNama.textContent || tdNama.innerText;
            if (textValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</body>
</html>