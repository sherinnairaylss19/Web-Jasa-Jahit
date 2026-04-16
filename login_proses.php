<?php
session_start();
include 'koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);
$cek = mysqli_num_rows($result);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($result);

    // Menyimpan data login ke session
    $_SESSION['login'] = true;
    $_SESSION['id_user'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    $_SESSION['nama'] = $data['username']; 
    $_SESSION['foto'] = "assets/default-profile.png"; 

    if ($data['role'] == "pemilik") {
        header("location:dashboard_pemilik.php");
    } else if ($data['role'] == "penjahit") {
        header("location:dashboard_penjahit.php");
    }
    exit(); 
} else {
    header("location:index.php?pesan=gagal");
    exit();
}
?>