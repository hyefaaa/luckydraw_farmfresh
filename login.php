<?php
session_start();

// ===========================================================
// TETAPKAN USERNAME & PASSWORD ADMIN DI SINI
// ===========================================================
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'ff2026'); // Tukar kepada password anda sendiri!
// ===========================================================

$error_msg = '';

// Jika admin dah login, terus redirect ke admin.php
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit();
}

// Proses login bila borang dihantar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = $_POST['username'] ?? '';
    $input_pass = $_POST['password'] ?? '';

    if ($input_user === ADMIN_USER && $input_pass === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $input_user;
        header('Location: admin.php');
        exit();
    } else {
        $error_msg = 'Username atau password salah. Sila cuba semula.';
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Farm Fresh Terengganu</title>
    <link rel="icon" type="image/x-icon" href="logo.png.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #f68b20;
            --primary-dark: #e05500;
            --bg: #fcf8f5;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --error: #ef4444;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-8px); }
            40%       { transform: translateX(8px); }
            60%       { transform: translateX(-5px); }
            80%       { transform: translateX(5px); }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        .login-card {
            background: var(--white);
            width: 100%;
            max-width: 420px;
            border-radius: 28px;
            padding: 40px 35px;
            box-shadow: 0 25px 50px rgba(246, 139, 32, 0.08);
            border-top: 10px solid var(--primary);
            text-align: center;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-card.error-shake {
            animation: shake 0.4s ease;
        }

        .logo {
            display: block;
            max-width: 120px;
            height: auto;
            margin: 0 auto 14px auto;
        }

        .badge-admin {
            display: block;
            text-align: center;
            background: rgba(246, 139, 32, 0.12);
            color: var(--primary-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 5px 14px;
            border-radius: 20px;
            margin: 0 auto 10px auto;
            text-transform: uppercase;
            width: fit-content;
        }

        h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 18px;
        }

        label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            pointer-events: none;
        }

        input[type="text"],
        input[type="password"] {
            font-family: 'Inter', sans-serif;
            width: 100%;
            padding: 13px 16px 13px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(246, 139, 32, 0.12);
        }

        input::placeholder { color: #94a3b8; font-weight: 400; }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            width: auto;
            margin: 0;
            box-shadow: none;
        }

        .toggle-password:hover {
            transform: translateY(-50%);
            background: none;
            box-shadow: none;
        }

        .error-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            text-align: left;
        }

        .error-box span {
            font-size: 13px;
            color: #dc2626;
            font-weight: 500;
            line-height: 1.4;
        }

        .btn-login {
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #f68b20 0%, #e05500 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 6px 20px rgba(246, 139, 32, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(246, 139, 32, 0.4);
        }

        .btn-login:active { transform: translateY(0); }

        .footer-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 30px;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 1.2px;
            font-weight: 700;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="login-card" id="loginCard">
    <img src="logo.png.png" alt="Logo Farm Fresh" class="logo">
    <div class="badge-admin">🔐 Admin Panel</div>
    <h1>Log Masuk Admin</h1>
    <p class="subtitle">Masukkan kelayakan anda untuk meneruskan</p>

    <?php if ($error_msg): ?>
    <div class="error-box">
        <span>❌</span>
        <span><?= htmlspecialchars($error_msg) ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="input-group">
            <label>Username</label>
            <div class="input-wrap">
                <span class="icon">👤</span>
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="username">
            </div>
        </div>

        <div class="input-group">
            <label>Password</label>
            <div class="input-wrap">
                <span class="icon">🔑</span>
                <input type="password" name="password" id="passwordInput" placeholder="Masukkan password" required autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePassword()" id="toggleBtn">👁️</button>
            </div>
        </div>

        <button type="submit" class="btn-login">MASUK KE PANEL ADMIN</button>
    </form>

    <div class="footer-text">TWIN MATRIX ENTERPRISE | FARM FRESH TERENGGANU</div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const btn   = document.getElementById('toggleBtn');
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerText = '🙈';
        } else {
            input.type = 'password';
            btn.innerText = '👁️';
        }
    }

    // Tambah animasi gegar bila ada error
    <?php if ($error_msg): ?>
    const card = document.getElementById('loginCard');
    card.style.animation = 'none';
    setTimeout(() => { card.style.animation = 'shake 0.4s ease'; }, 10);
    <?php endif; ?>
</script>

</body>
</html>
