<?php
session_start();

// Usuario y contraseña hardcodeados
$USER = 'admin';
$PASS = '@m1k0@pp';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $USER && $password === $PASS) {
        $_SESSION['admin_logged'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin</title>
<style>
body{font-family:system-ui,Segoe UI,Roboto,Arial;background:#f7f9fc;margin:0;padding:0;display:flex;align-items:center;justify-content:center;height:100vh}
.login-box{background:#fff;padding:24px;border-radius:10px;box-shadow:0 8px 30px rgba(10,20,40,0.06);width:320px}
.login-box h1{margin-top:0;text-align:center;font-size:1.5rem}
.login-box input{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc;font-size:1rem}
.login-box button{width:100%;padding:10px;margin-top:12px;border:none;border-radius:6px;background:#1E3A8A;color:#fff;font-size:1rem;cursor:pointer}
.error{color:red;text-align:center;font-size:0.9rem;margin-top:6px}
</style>
</head>
<body>
<div class="login-box">
  <h1>Admin Login</h1>
  <form method="POST">
    <input type="text" name="username" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
  <?php if($error) echo '<div class="error">'.$error.'</div>'; ?>
</div>
</body>
</html>
