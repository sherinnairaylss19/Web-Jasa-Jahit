<?php
session_start();
include 'koneksi.php';

if (isset($_POST['id_token'])) {
    $id_token = $_POST['id_token'];

    
    $parts = explode(".", $id_token);
    if (count($parts) != 3) exit("Token tidak valid");
    
    $payload = json_decode(base64_decode($parts[1]), true);
    
    $email = $payload['email'];
    $nama  = $payload['name'];
    $foto  = $payload['picture'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    $user  = mysqli_fetch_assoc($query);

    if ($user) {
        // Jika email terdaftar, simpan identitas ke SESSION
        $_SESSION['login'] = true;
        $_SESSION['nama']  = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role']  = $user['role']; 
        $_SESSION['foto']  = $foto;

        if ($user['role'] == 'pemilik') {
            header("Location: dashboard_pemilik.php");
        } else if ($user['role'] == 'penjahit') {
            header("Location: dashboard_penjahit.php");
        }
        exit();
    } else {
        // Jika email tidak ada di database, balik ke login
        header("Location: index.php?pesan=gagal");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>