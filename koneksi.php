<?php
session_start();
// Sesuaikan dengan nama database kamu
$koneksi = mysqli_connect("localhost", "root", "", "nama_database_kamu");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}

$username = $_POST['username'];
$password = $_POST['password'];

// Mencari user berdasarkan email (karena di tabelmu adanya email)
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0) {
    $data = mysqli_fetch_assoc($query);
    
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['email'] = $data['email'];
    $_SESSION['role'] = $data['role'];
    $_SESSION['status'] = "login";

    // Arahkan ke dashboard sesuai role
    header("location:dashboard.php");
} else {
    header("location:index.php?pesan=gagal");
}
?>