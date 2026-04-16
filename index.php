<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Jahit Dua Saudara</title>
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
            padding: 40px;
            border-radius: 25px;
            text-align: center;
            width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .logo-circle {
            background-color: #2D5E55;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
        }

        .logo-circle img { width: 50px; }

        h1 { color: white; font-size: 24px; margin-bottom: 5px; }
        p { color: rgba(255, 255, 255, 0.8); margin-bottom: 25px; font-size: 14px; }

        /* Styling Input Form */
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            outline: none;
        }
        .input-group input::placeholder { color: rgba(255,255,255,0.6); }

        .btn-login {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #2D5E55;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover { background: #1f423c; }

        .alert {
            color: #ff6b6b;
            background: rgba(255,0,0,0.1);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-circle">
            <img src="assets/logo-login.png" alt="Logo">
        </div>

        <h1>Toko Jahit Dua Saudara</h1>
        <p>Silakan masuk ke akun Anda</p>

        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
            <div class="alert">Username atau Password salah!</div>
        <?php endif; ?>

        <form action="login_proses.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" class="btn-login">MASUK</button>
        </form>
    </div>

</body>
</html>