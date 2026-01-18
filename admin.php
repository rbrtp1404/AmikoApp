<?php
session_start();

// --- CONFIGURACIÓN LOGIN ---
$USER = 'admin';
$PASS = 'Amiko2025';
$loginError = false;

// Procesar login si se envió POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    if ($_POST['username'] === $USER && $_POST['password'] === $PASS) {
        $_SESSION['admin_logged'] = true;
    } else {
        $loginError = true;
    }
}

// Si no está logueado, mostrar formulario de login
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true):
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Amiko</title>
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
  <?php if($loginError) echo '<div class="error">Usuario o contraseña incorrectos</div>'; ?>
</div>
</body>
</html>
<?php
exit; // detener ejecución si no está logueado
endif;

// --- LÓGICA ADMIN (si está logueado) ---
$csvFile = 'signups.csv';
$rows = [];
if (file_exists($csvFile)) {
    $rows = array_map('str_getcsv', file($csvFile));
    $headers = array_shift($rows); // quitar cabecera
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Amiko</title>
<style>
body{font-family:system-ui,Segoe UI,Roboto,Arial;background:#f7f9fc;margin:20px;color:#111}
.wrap{max-width:960px;margin:0 auto;background:#fff;padding:18px;border-radius:10px;box-shadow:0 8px 30px rgba(10,20,40,0.06)}
h1{margin-top:0}
table{width:100%;border-collapse:collapse;margin-top:12px}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;font-size:0.95rem}
th{background:#fafafa}
.controls{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
button{padding:8px 12px;border-radius:8px;border:1px solid #ccc;background:#fff;cursor:pointer}
button.danger{background:#fee; border-color:#f88}
.muted{color:#666;font-size:0.9rem}
</style>
</head>
<body>
<div class="wrap">
  <h1>Registros — Amiko</h1>
  <div class="controls">
    <button onclick="location.reload()">Refrescar</button>
    <button onclick="exportCSV()">Exportar CSV</button>
    <button class="danger" onclick="clearCSV()">Borrar todo</button>
    <div class="muted"><?php echo count($rows); ?> registros</div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Registrado</th>
      </tr>
    </thead>
    <tbody>
    <?php if($rows): ?>
      <?php foreach(array_reverse($rows) as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r[1]) ?></td>
          <td><?= htmlspecialchars($r[2]) ?></td>
          <td><?= htmlspecialchars($r[3]) ?></td>
          <td><?= isset($r[4]) ? date('d/m/Y H:i:s', strtotime($r[4])) : '-' ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="4" style="text-align:center;color:#666;">No hay registros</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
// Exportar a CSV
function exportCSV(){
  fetch('signups.csv')
    .then(r=>r.blob())
    .then(blob=>{
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = 'amiko_signups.csv';
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    })
    .catch(e=>alert('Error al exportar CSV'));
}

// Borrar CSV
function clearCSV(){
  if(!confirm('¿Borrar todos los registros? Esto no se puede deshacer.')) return;
  fetch('delete_csv.php')
    .then(()=>location.reload())
    .catch(()=>alert('No se pudo borrar'));
}
</script>
</body>
</html>
