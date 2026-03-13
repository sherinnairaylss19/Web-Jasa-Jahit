<?php
session_start();
session_destroy(); 
?>

<script>
 
    sessionStorage.removeItem('namaAdmin');
    sessionStorage.removeItem('fotoAdmin');
  
    alert("Anda telah berhasil keluar.");
    window.location.href = "login.html";
</script>