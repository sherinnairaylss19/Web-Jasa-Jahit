<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_lengkap  = $_POST['nama_lengkap'];
    $no_hp         = $_POST['no_hp'];
    $alamat_lengkap= $_POST['alamat_lengkap'];
    $tgl_masuk     = $_POST['tgl_masuk'];
    $tgl_tenggat   = $_POST['tgl_tenggat'];
    $total_biaya   = $_POST['total_biaya'];
    $jenis_pesanan = $_POST['jenis_pesanan'];
    $catatan       = $_POST['catatan'];
    $status_produksi = "Proses"; // Status awal otomatis 'Proses'

    $query = "INSERT INTO pesanan (nama_lengkap, no_hp, alamat_lengkap, tgl_masuk, tgl_tenggat, total_biaya, jenis_pesanan, catatan, status_produksi) 
              VALUES ('$nama_lengkap', '$no_hp', '$alamat_lengkap', '$tgl_masuk', '$tgl_tenggat', '$total_biaya', '$jenis_pesanan', '$catatan', '$status_produksi')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pesanan berhasil disimpan!'); window.location='pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>