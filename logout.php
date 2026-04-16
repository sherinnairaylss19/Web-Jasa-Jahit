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