<?php
session_start();
include '../config/config.php';
if(isset($_POST['login'])) {
    $user = trim($_POST['username']);
    $pass = trim($_POST['password']);
    // Contoh: admin/admin123
    if($user=='admin' && $pass=='admin123') {
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php'); exit;
    } else {
        $error = 'Login gagal!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Kos</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        .login-box {max-width:350px;margin:60px auto;background:#e9f5db;padding:30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.08);}
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Admin</h2>
        <?php if(isset($error)): ?><div class="msg error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <label>Username</label><br>
            <input type="text" name="username" required><br>
            <label>Password</label><br>
            <input type="password" name="password" required><br><br>
            <button class="btn" type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
