<?php
session_start();
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id_pesanan   = $_POST['id_pesanan'];
    $jumlah_bayar = str_replace('.', '', $_POST['uang_muka']);
    $status_bayar = $_POST['status_bayar'];

    $q_pesanan = mysqli_query($koneksi, "SELECT total_biaya FROM pesanan WHERE id_pesanan = '$id_pesanan'");
    $total_biaya = mysqli_fetch_assoc($q_pesanan)['total_biaya'];

    $q_total_masuk = mysqli_query($koneksi, "SELECT SUM(uang_muka) AS total_sudah_bayar FROM pembayaran WHERE id_pesanan = '$id_pesanan'");
    $sudah_bayar = mysqli_fetch_assoc($q_total_masuk)['total_sudah_bayar'];

    $sisa_bayar = ($total_biaya - $sudah_bayar) - $jumlah_bayar;
    if ($sisa_bayar < 0) $sisa_bayar = 0;

    $query = "INSERT INTO pembayaran (id_pesanan, tgl_pembayaran, uang_muka, sisa_bayar, status_bayar) 
              VALUES ('$id_pesanan', NOW(), '$jumlah_bayar', '$sisa_bayar', '$status_bayar')";
    
    if (mysqli_query($koneksi, $query)) {
        if ($sisa_bayar == 0) {
            mysqli_query($koneksi, "UPDATE pesanan SET status_pembayaran = 'Lunas' WHERE id_pesanan = '$id_pesanan'");
        }
        echo "<script>alert('Pembayaran berhasil ditambahkan!'); window.location='pembayaran.php';</script>";
    }
}
?>