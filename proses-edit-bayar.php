<?php
$koneksi = mysqli_connect("localhost", "root", "", "toko-jahit");

if (isset($_POST['update'])) {
    $id = $_POST['id_pembayaran'];
    $uang_muka = $_POST['uang_muka'];
    $status_bayar = $_POST['status_bayar'];

    $q_harga = mysqli_query($koneksi, "SELECT pesanan.total_biaya FROM pembayaran 
                                       JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan 
                                       WHERE id_pembayaran = '$id'");
    $d_harga = mysqli_fetch_assoc($q_harga);
    $sisa_bayar = $d_harga['total_biaya'] - $uang_muka;
   
    if ($sisa_bayar <= 0) {
    $sisa_bayar = 0;
    $status_bayar = 'Lunas';
    } else {
    $status_bayar = 'DP'; 
    }

$sql = "UPDATE pembayaran SET 
        uang_muka = '$uang_muka', 
        sisa_bayar = '$sisa_bayar', 
        status_bayar = '$status_bayar' 
        WHERE id_pembayaran = '$id'";
    $sql = "UPDATE pembayaran SET 
            uang_muka = '$uang_muka', 
            sisa_bayar = '$sisa_bayar', 
            status_bayar = '$status_bayar' 
            WHERE id_pembayaran = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: pembayaran.php");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>