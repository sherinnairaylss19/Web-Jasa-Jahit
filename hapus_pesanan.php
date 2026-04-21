<?php
session_start();
include 'koneksi.php';

// Proteksi akses
if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];

    $query_hapus = mysqli_query($koneksi, "DELETE FROM pesanan WHERE id_pesanan = '$id_pesanan'");

    if ($query_hapus) {
        echo "<script>alert('Pesanan berhasil dihapus'); window.location='pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: pesanan.php");
}
?>