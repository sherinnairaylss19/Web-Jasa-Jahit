<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pemilik') {
    header("Location: index.php");
    exit();
}

$bulan_pilihan = isset($_GET['bulan']) ? str_pad($_GET['bulan'], 2, '0', STR_PAD_LEFT) : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

// Query kas masuk (kecuali yang sudah dikembalikan)
$query_laporan = mysqli_query($koneksi, "
    SELECT 
        pb.tgl_pembayaran AS tanggal_bayar,
        ps.id_pesanan,
        ps.jenis_pesanan,
        ps.total_biaya,
        pb.uang_muka AS kas_masuk,
        pb.sisa_bayar,
        pb.status_bayar,
        pl.nama_lengkap AS nama_pelanggan,
        ps.status_produksi
    FROM pembayaran pb
    INNER JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan
    INNER JOIN pelanggan pl ON ps.id_pelanggan = pl.id_pelanggan
    WHERE ps.is_deleted = 0
      AND pb.status_bayar != 'Dikembalikan'
      AND pb.uang_muka > 0
      AND DATE_FORMAT(pb.tgl_pembayaran, '%m') = '$bulan_pilihan'
      AND DATE_FORMAT(pb.tgl_pembayaran, '%Y') = '$tahun_pilihan'
    ORDER BY pb.tgl_pembayaran ASC
");

// Total kas masuk
$query_total = mysqli_query($koneksi, "
    SELECT SUM(pb.uang_muka) AS total_kas
    FROM pembayaran pb
    INNER JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan
    WHERE ps.is_deleted = 0
      AND pb.status_bayar != 'Dikembalikan'
      AND pb.uang_muka > 0
      AND DATE_FORMAT(pb.tgl_pembayaran, '%m') = '$bulan_pilihan'
      AND DATE_FORMAT(pb.tgl_pembayaran, '%Y') = '$tahun_pilihan'
");
$data_total = mysqli_fetch_assoc($query_total);
$total_kas  = $data_total['total_kas'] ?? 0;

// Total piutang
$query_piutang = mysqli_query($koneksi, "
    SELECT SUM(pb.sisa_bayar) AS total_piutang
    FROM pembayaran pb
    INNER JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan
    WHERE ps.is_deleted = 0
      AND pb.status_bayar != 'Dikembalikan'
      AND pb.sisa_bayar > 0
      AND DATE_FORMAT(pb.tgl_pembayaran, '%m') = '$bulan_pilihan'
      AND DATE_FORMAT(pb.tgl_pembayaran, '%Y') = '$tahun_pilihan'
");
$data_piutang  = mysqli_fetch_assoc($query_piutang);
$total_piutang = $data_piutang['total_piutang'] ?? 0;

// Query riwayat pengembalian uang
$query_kembali = mysqli_query($koneksi, "
    SELECT 
        pb.tgl_pembayaran AS tanggal_bayar,
        pl.nama_lengkap AS nama_pelanggan,
        ps.jenis_pesanan,
        ps.total_biaya,
        pb.uang_muka AS uang_dikembalikan
    FROM pembayaran pb
    INNER JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan
    INNER JOIN pelanggan pl ON ps.id_pelanggan = pl.id_pelanggan
    WHERE ps.is_deleted = 0
      AND pb.status_bayar = 'Dikembalikan'
      AND pb.uang_muka > 0
      AND DATE_FORMAT(pb.tgl_pembayaran, '%m') = '$bulan_pilihan'
      AND DATE_FORMAT(pb.tgl_pembayaran, '%Y') = '$tahun_pilihan'
    ORDER BY pb.tgl_pembayaran ASC
");

// Total uang dikembalikan
$query_total_kembali = mysqli_query($koneksi, "
    SELECT SUM(pb.uang_muka) AS total_kembali
    FROM pembayaran pb
    INNER JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan
    WHERE ps.is_deleted = 0
      AND pb.status_bayar = 'Dikembalikan'
      AND pb.uang_muka > 0
      AND DATE_FORMAT(pb.tgl_pembayaran, '%m') = '$bulan_pilihan'
      AND DATE_FORMAT(pb.tgl_pembayaran, '%Y') = '$tahun_pilihan'
");
$data_kembali  = mysqli_fetch_assoc($query_total_kembali);
$total_kembali = $data_kembali['total_kembali'] ?? 0;

$nama_bulan_arr = [
    '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
    '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
    '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
];
$nama_bulan = $nama_bulan_arr[$bulan_pilihan] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/laporan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge-dibatalkan {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-belum-kembali {
            font-size: 11px;
            color: #b45309;
            font-weight: 600;
            display: inline-block;
            margin-top: 3px;
        }
        .sum-card.red {
            background: #fff5f5;
            border-left: 4px solid #dc2626;
        }
        .table-pengembalian h3 {
            color: #dc2626;
        }
        .table-pengembalian {
            margin-top: 24px;
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="sidebar"><?php include 'sidebar.php'; ?></nav>
    <div class="main-content">
        <header class="top-bar"><h1 class="top-bar-title">Laporan Keuangan</h1></header>
        <div class="content-body">

            <!-- Filter Bulan & Tahun -->
            <form method="GET" class="filter-bar">
                <select name="bulan">
                    <?php foreach ($nama_bulan_arr as $val => $lbl) {
                        $sel = ($val === $bulan_pilihan) ? 'selected' : '';
                        echo "<option value='$val' $sel>$lbl</option>";
                    } ?>
                </select>
                <input type="number" name="tahun" value="<?= $tahun_pilihan ?>" min="2000" max="2099" style="width:90px;">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
            </form>

            <!-- Kartu Ringkasan -->
           <div class="summary-grid">
                <div class="sum-card green">
                    <div class="icon">
                        <i class="fa-solid fa-money-bill-wave" style="color:#2D5E55;"></i>
                    </div>
                    <div>
                        <p>Total Kas Masuk</p>
                        <h3>Rp <?= number_format($total_kas, 0, ',', '.') ?></h3>
                        <p style="font-size:11px; margin-top:2px;"><?= $nama_bulan ?> <?= $tahun_pilihan ?></p>
                    </div>
                </div>
                <div class="sum-card orange">
                    <div class="icon">
                        <i class="fa-solid fa-clock" style="color:#f59e0b;"></i>
                    </div>
                    <div>
                        <p>Total Piutang</p>
                        <h3>Rp <?= number_format($total_piutang, 0, ',', '.') ?></h3>
                        <p style="font-size:11px; margin-top:2px;">Belum terlunasi</p>
                    </div>
                </div>
                <div class="sum-card red">
                    <div class="icon">
                        <i class="fa-solid fa-rotate-left" style="color:#dc2626;"></i>
                    </div>
                    <div>
                        <p>Total Dikembalikan</p>
                        <h3 style="color:#dc2626;">Rp <?= number_format($total_kembali, 0, ',', '.') ?></h3>
                        <p style="font-size:11px; margin-top:2px;">Pesanan dibatalkan</p>
                    </div>
                </div>
            </div>

            <!-- Tabel Kas Masuk -->
            <div class="table-section">
                <h3>Rincian Kas Masuk — <?= $nama_bulan ?> <?= $tahun_pilihan ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Pelanggan</th>
                            <th>Jenis Pesanan</th>
                            <th>Total Tagihan</th>
                            <th>Kas Masuk (Bayar)</th>
                            <th>Sisa Piutang</th>
                            <th>Status Bayar</th>
                            <th>Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_kolom_tagihan = 0;
                        $total_kolom_bayar   = 0;
                        $total_kolom_sisa    = 0;

                        if (mysqli_num_rows($query_laporan) > 0):
                            while ($row = mysqli_fetch_assoc($query_laporan)):
                                $total_kolom_tagihan += $row['total_biaya'];
                                $total_kolom_bayar   += $row['kas_masuk'];
                                $total_kolom_sisa    += $row['sisa_bayar'];
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_bayar'])) ?></td>
                            <td><strong><?= htmlspecialchars($row['nama_pelanggan']) ?></strong></td>
                            <td><?= htmlspecialchars($row['jenis_pesanan']) ?></td>
                            <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></td>
                            <td style="color:#2D5E55; font-weight:bold;">Rp <?= number_format($row['kas_masuk'], 0, ',', '.') ?></td>
                            <td style="color:#dc2626; font-weight:bold;">Rp <?= number_format($row['sisa_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php if (strtolower($row['status_bayar']) == 'lunas'): ?>
                                    <span class="badge badge-lunas">LUNAS</span>
                                <?php else: ?>
                                    <span class="badge badge-dp">DP</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status_produksi'] === 'Dibatalkan'): ?>
                                    <span class="badge-dibatalkan">Dibatalkan</span>
                                    <br>
                                    <span class="badge-belum-kembali">Belum Dikembalikan</span>
                                <?php else: ?>
                                    <span><?= htmlspecialchars($row['status_produksi']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="9" style="text-align:center; padding:20px; color:#9ca3af; font-family:sans-serif;">
                                Tidak ada transaksi pembayaran pada <?= $nama_bulan ?> <?= $tahun_pilihan ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (mysqli_num_rows($query_laporan) > 0): ?>
                    <tfoot>
                        <tr style="background:#f2f2f2; font-weight:700; font-family:sans-serif;">
                            <td colspan="4" style="text-align:right; padding:12px;">TOTAL BULAN INI</td>
                            <td>Rp <?= number_format($total_kolom_tagihan, 0, ',', '.') ?></td>
                            <td style="color:#2D5E55;">Rp <?= number_format($total_kolom_bayar, 0, ',', '.') ?></td>
                            <td style="color:#dc2626;">Rp <?= number_format($total_kolom_sisa, 0, ',', '.') ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Tabel Riwayat Pengembalian (hanya tampil jika ada) -->
            <?php if ($total_kembali > 0): ?>
            <div class="table-section table-pengembalian">
                <h3>Riwayat Pengembalian Uang — <?= $nama_bulan ?> <?= $tahun_pilihan ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Jenis Pesanan</th>
                            <th>Total Tagihan</th>
                            <th>Uang Dikembalikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no_k = 1;
                        while ($r = mysqli_fetch_assoc($query_kembali)):
                        ?>
                        <tr>
                            <td><?= $no_k++ ?></td>
                            <td><?= date('d M Y', strtotime($r['tanggal_bayar'])) ?></td>
                            <td><strong><?= htmlspecialchars($r['nama_pelanggan']) ?></strong></td>
                            <td><?= htmlspecialchars($r['jenis_pesanan']) ?></td>
                            <td>Rp <?= number_format($r['total_biaya'], 0, ',', '.') ?></td>
                            <td style="color:#dc2626; font-weight:bold;">
                                - Rp <?= number_format($r['uang_dikembalikan'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#fee2e2; font-weight:700; font-family:sans-serif;">
                            <td colspan="5" style="text-align:right; padding:12px;">TOTAL DIKEMBALIKAN</td>
                            <td style="color:#dc2626;">- Rp <?= number_format($total_kembali, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>

            <!-- Tombol Cetak -->
            <div class="action-footer">
                <button class="btn-print" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Cetak Halaman Ini
                </button>
            </div>

        </div>
    </div>
</div>
</body>
</html>