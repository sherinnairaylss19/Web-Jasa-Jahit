<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $tgl_pembayaran = $_POST['tgl_pembayaran'];
    $uang_muka = $_POST['uang_muka'];
    $status_bayar = $_POST['status_bayar'];

    $query_harga = mysqli_query($koneksi, "SELECT total_biaya FROM pesanan WHERE id_pesanan = '$id_pesanan'");
    $data_harga = mysqli_fetch_assoc($query_harga);
    $total_biaya = $data_harga['total_biaya'];

    $sisa_bayar = $total_biaya - $uang_muka;

    $query = "INSERT INTO pembayaran (id_pesanan, tgl_pembayaran, uang_muka, sisa_bayar, status_bayar) 
              VALUES ('$id_pesanan', '$tgl_pembayaran', '$uang_muka', '$sisa_bayar', '$status_bayar')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Pembayaran berhasil disimpan!');
                window.location='pembayaran.php';
              </script>";
    } else {
        echo "Gagal menyimpan: " . mysqli_error($koneksi);
    }
}
?>