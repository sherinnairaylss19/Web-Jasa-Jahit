<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logout...</title>
</head>
<body>
    <script>

        sessionStorage.removeItem('namaAdmin');
        sessionStorage.removeItem('fotoAdmin');
    
        alert("Anda telah berhasil keluar.");

        window.location.href = "index.html";
    </script>
</body>
</html>
=======
<?php
session_start();

$_SESSION = [];

session_destroy();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

echo "<script>
    alert('Anda telah berhasil keluar.');
    window.location.href = 'index.php'; 
</script>";
exit();
?>
>>>>>>> d623d883a4a5dbadbc0fb9f53af58886177a66b9
