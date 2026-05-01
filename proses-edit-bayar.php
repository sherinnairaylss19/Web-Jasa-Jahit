<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    $id_pembayaran = $_POST['id_pembayaran'];
    $sudah_bayar_lama = $_POST['sudah_bayar_lama'];
    $tambahan_bayar = $_POST['tambahan_bayar'];
    $total_biaya = $_POST['total_biaya'];
    $status_bayar = $_POST['status_bayar'];

    $total_baru = $sudah_bayar_lama + $tambahan_bayar;
    $sisa_baru = $total_biaya - $total_baru;

    if ($sisa_baru <= 0) {
        $status_bayar = 'Lunas';
        $sisa_baru = 0;
    }

    $query = "UPDATE pembayaran SET 
              uang_muka = '$total_baru', 
              sisa_bayar = '$sisa_baru', 
              status_bayar = '$status_bayar' 
              WHERE id_pembayaran = '$id_pembayaran'";

    if (mysqli_query($koneksi, $query)) {
        if ($status_bayar == 'Lunas') {
            $get_id = mysqli_query($koneksi, "SELECT id_pesanan FROM pembayaran WHERE id_pembayaran = '$id_pembayaran'");
            $id_p = mysqli_fetch_assoc($get_id)['id_pesanan'];
            mysqli_query($koneksi, "UPDATE pesanan SET status_pembayaran = 'Lunas' WHERE id_pesanan = '$id_p'");
        }
        echo "<script>alert('Pembayaran Berhasil Diupdate!'); window.location='pembayaran.php';</script>";
    }
}
?>