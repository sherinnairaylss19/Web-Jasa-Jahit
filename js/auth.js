document.addEventListener("DOMContentLoaded", function() {
            
            const nama = sessionStorage.getItem('namaAdmin');
            const foto = sessionStorage.getItem('fotoAdmin');

            const elemenNama = document.getElementById('user-nama');
            const elemenFoto = document.getElementById('user-foto');
            const elemenIkon = document.getElementById('user-icon');

            if (nama && foto) {
                
                elemenNama.innerText = nama;
                
                elemenFoto.src = foto;
                elemenFoto.style.display = 'block'; 
                
                if(elemenIkon) elemenIkon.style.display = 'none';
            } else {
                
                alert("Sesi tidak ditemukan. Silakan login kembali.");
                window.location.href = "login.php";
            }
        });