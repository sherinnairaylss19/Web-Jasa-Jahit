<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: pembayaran.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

$cek = mysqli_query($koneksi, "
    SELECT pb.*, ps.status_produksi 
    FROM pembayaran pb 
    JOIN pesanan ps ON pb.id_pesanan = ps.id_pesanan 
    WHERE pb.id_pembayaran = '$id'
");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    header("Location: pembayaran.php");
    exit();
}

if ($data['status_produksi'] !== 'Dibatalkan') {
    $_SESSION['error'] = "Pesanan ini tidak dibatalkan, tidak bisa mengembalikan uang.";
    header("Location: pembayaran.php");
    exit();
}

if (strtolower($data['status_bayar']) === 'dikembalikan') {
    $_SESSION['error'] = "Uang muka sudah dikembalikan sebelumnya.";
    header("Location: pembayaran.php");
    exit();
}

$update = mysqli_query($koneksi, "
    UPDATE pembayaran 
    SET sisa_bayar   = 0, 
        status_bayar = 'Dikembalikan'
    WHERE id_pembayaran = '$id'
");

if ($update) {
    $_SESSION['sukses'] = "Uang muka berhasil dikembalikan ke pelanggan.";
} else {
    $_SESSION['error'] = "Gagal memperbarui data pembayaran.";
}

header("Location: pembayaran.php");
exit();