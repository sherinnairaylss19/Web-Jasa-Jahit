<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

if (isset($_GET['id'])) {
    $id_pesanan = mysqli_real_escape_string($koneksi, $_GET['id']);

    $sql = "UPDATE pesanan SET is_deleted = 1 WHERE id_pesanan = '$id_pesanan'";
    $eksekusi = mysqli_query($koneksi, $sql);

    if ($eksekusi) {
        echo "<script>
                alert('Pesanan berhasil dihapus'); 
                window.location='pesanan.php';
              </script>";
    } else {
        echo "Error Database: " . mysqli_error($koneksi);
    }
} else {
    header("Location: pesanan.php");
    exit();
}
?>