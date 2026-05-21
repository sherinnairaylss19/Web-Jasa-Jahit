<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$id_pesanan = (int)$_GET['id'];

$cek = mysqli_query($koneksi, "SELECT id_pesanan FROM pesanan WHERE id_pesanan = $id_pesanan AND is_deleted = 0");
if (mysqli_num_rows($cek) === 0) {
    header("Location: pesanan.php");
    exit();
}

mysqli_query($koneksi, "UPDATE pesanan SET status_produksi = 'Dibatalkan' WHERE id_pesanan = $id_pesanan");

header("Location: pesanan.php");
exit();
?>