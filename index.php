<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Jahit Dua Saudara</title>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('assets/bg-login.jpeg'); 
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(15px); 
            -webkit-backdrop-filter: blur(15px);
            padding: 50px 40px;
            border-radius: 25px;
            text-align: center;
            width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .logo-circle {
            background-color: #2D5E55;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .logo-circle img {
            width: 80px;
        }

        h1 {
            color: white;
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
            margin-bottom: 35px;
        }

        #google-btn-container {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-circle">
            <img src="assets/logo-login.png" alt="Logo">
        </div>

        <h1>Toko Jahit Dua Saudara</h1>
        <p>Masuk ke akun Anda</p>

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
            <div class="alert">Email tidak terdaftar dalam sistem!</div>
        <?php endif; ?>

        <div id="google-btn-container">
            <div id="g_id_onload"
                data-client_id="735257234122-kishc6k5nlqu62hanah4da5c4lcpf85c.apps.googleusercontent.com"
                data-callback="handleCredentialResponse"
                data-auto_prompt="false">
            </div>
            <div class="g_id_signin"
                data-type="standard"
                data-size="large"
                data-theme="filled_blue"
                data-text="signin_with"
                data-shape="pill"
                data-logo_alignment="left">
            </div>
        </div>
    </div>

    <script>
    function handleCredentialResponse(response) {
        // Ambil token dari Google
        const id_token = response.credential;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'auth_google.php'; // Nama file PHP pengolah login

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_token';
        input.value = id_token;

        form.appendChild(input);
        document.body.appendChild(form);
        
        form.submit();
    }
    </script>

</body>
</html>