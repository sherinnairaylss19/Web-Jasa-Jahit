<?php
// 1. Pastikan session dimulai paling atas
session_start();

// 2. Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "toko-jahit");

if (mysqli_connect_errno()) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

// 3. Ambil dan bersihkan input
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// 4. Query ke database
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");

if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

$cek = mysqli_num_rows($query);

if($cek > 0) {
    $data = mysqli_fetch_assoc($query);
    
    // Simpan ke Session
    $_SESSION['nama']     = $data['nama'];
    $_SESSION['username'] = $data['username']; 
    $_SESSION['role']     = $data['role'];
    $_SESSION['status']   = "login";

    // DEBUGGING: Hapus tanda // di bawah ini jika masih putih polos untuk melihat error
    // echo "Data ditemukan! Role anda: " . $data['role']; exit();

    // 5. Gunakan trim() dan strtolower() sekaligus agar tidak ada celah spasi/huruf besar
    $role_bersih = strtolower(trim($data['role']));

    if ($role_bersih == 'pemilik') {
        header("Location: dashboard_pemilik.php");
        exit();
    } else if ($role_bersih == 'penjahit') {
        header("Location: dashboard_penjahit.php");
        exit();
    } else {
        echo "Role tidak dikenali: " . $data['role'];
        exit();
    }
} else {
    // Jika tidak ditemukan, arahkan balik dengan pesan gagal
    header("Location: index.php?pesan=gagal");
    exit();
}
?>