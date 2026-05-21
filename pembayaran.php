<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT 
            pembayaran.id_pembayaran,
            pembayaran.id_pesanan,
            pelanggan.nama_lengkap, 
            pesanan.tgl_masuk,
            pesanan.tgl_tenggat,
            pesanan.total_biaya,
            pembayaran.uang_muka,
            pembayaran.sisa_bayar,
            pembayaran.status_bayar,
            pesanan.status_produksi
        FROM pembayaran
        JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan
        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
        WHERE pesanan.is_deleted = 0
        ORDER BY pembayaran.id_pembayaran DESC";

$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link rel="stylesheet" href="css/pembayaran.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .btn-kembalikan {
            background-color: #dc2626;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-kembalikan:hover { background-color: #b91c1c; }

        .status_produksi {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar">
        <?php include 'sidebar.php'; ?>
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
                                <th>Status Pesanan</th>
                                <th>Status Pengembalian</th>
                                <th style="text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $ada_data = false;
                            while($row = mysqli_fetch_assoc($query)) :
                                $ada_data = true;

                                $status_produksi = $row['status_produksi'];
                                if ($status_produksi === 'Proses' && $row['tgl_tenggat'] < date('Y-m-d')) {
                                    $status_produksi = 'Telat';
                                }
                                $status_class = strtolower(str_replace(' ', '-', $status_produksi));

                                $status_bayar = strtolower($row['status_bayar']);
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong><?= htmlspecialchars($row['nama_lengkap']); ?></strong></td>
                                <td><?= date('d M Y', strtotime($row['tgl_masuk'])); ?></td>
                                <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['uang_muka'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($row['sisa_bayar'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($status_bayar == 'lunas'): ?>
                                        <span class="status selesai">Lunas</span>
                                    <?php elseif ($status_bayar == 'dikembalikan'): ?>
                                        <span class="status" style="background:#fef9c3;color:#854d0e;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block;">Dikembalikan</span>
                                    <?php else: ?>
                                        <span class="status proses">DP</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status_produksi <?= $status_class ?>">
                                        <?= htmlspecialchars($status_produksi) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($status_bayar === 'dikembalikan'): ?>
                                        <span style="background:#d1fae5;color:#065f46;border:1px; padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block;">
                                            Berhasil Dikembalikan
                                        </span>
                                    <?php elseif ($row['status_produksi'] === 'Dibatalkan' && $row['uang_muka'] > 0): ?>
                                        <span style="background:#fee2e2;color:#991b1b;border:1px; padding:4px 10px;border-radius:20px;font-size:12px;font-weight:600;display:inline-block;">
                                            Belum Dikembalikan
                                        </span>
                                    <?php else: ?>
                                        <span style="color:#9ca3af;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="aksi-buttons">
                                    <div class="button-group">
                                        <?php if ($status_bayar === 'dikembalikan'): ?>
                                            <span style="color:#9ca3af;">-</span>
                                        <?php elseif ($row['status_produksi'] === 'Dibatalkan' && $row['uang_muka'] > 0): ?>
                                            <a href="proses-kembalikan-uang.php?id=<?= $row['id_pembayaran']; ?>" 
                                               class="btn-kembalikan"
                                               onclick="return confirm('Yakin ingin mengembalikan uang muka ke pelanggan? Tindakan ini tidak bisa dibatalkan.')">
                                               Kembalikan Uang
                                            </a>
                                        <?php elseif ($row['status_produksi'] === 'Dibatalkan' && $row['uang_muka'] == 0): ?>
                                            <span style="color:#9ca3af;">-</span>
                                        <?php else: ?>
                                            <a href="edit-bayar.php?id=<?= $row['id_pembayaran']; ?>" class="btn-edit-bayar">Edit</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if (!$ada_data): ?>
                                <tr>
                                    <td colspan="10" style="text-align:center; padding:20px; color:#9ca3af;">
                                        Tidak ada data pembayaran.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    const searchInput = document.querySelector('.search-box input');
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        const tr = document.querySelectorAll('tbody tr');
        tr.forEach(row => {
            const nama = row.cells[1]?.innerText.toLowerCase() ?? '';
            row.style.display = nama.includes(filter) ? "" : "none";
        });
    });
    </script>
</body>
</html>