<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Kullanıcı adı ve şifreyi buradan değiştirebilirsin
    if ($username === 'admin' && $password === '1234') {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Kullanıcı adı veya şifre hatalı!';
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi – Celaloğlu Defransiyel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #0f0f0f;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-box {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            color: #e8b84b;
            text-align: center;
            margin-bottom: 8px;
            font-size: 22px;
        }
        .login-box p {
            color: #888;
            text-align: center;
            font-size: 13px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            color: #ccc;
            font-size: 13px;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            background: #2a2a2a;
            border: 1px solid #444;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            border-color: #e8b84b;
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #e8b84b;
            color: #000;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover { background: #d4a535; }
        .error {
            background: #3a1a1a;
            border: 1px solid #c0392b;
            color: #e74c3c;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>⚙️ Admin Paneli</h2>
        <p>Celaloğlu Defransiyel Antalya</p>

        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" placeholder="admin" required autofocus>
            </div>
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login">Giriş Yap</button>
        </form>
    </div>
</body>
</html>
