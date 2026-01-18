<?php
// save.php
header('Content-Type: application/json');

$csvFile = 'signups.csv';

// Obtener datos del POST
$name  = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

// Validación básica
if (!$name || strlen($name)<2) {
    echo json_encode(['status'=>'error','message'=>'Nombre inválido']);
    exit;
}
if (!$phone || !preg_match('/^\+?[0-9\s\-]{7,}$/', $phone)) {
    echo json_encode(['status'=>'error','message'=>'Teléfono inválido']);
    exit;
}
if (!$email || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status'=>'error','message'=>'Email inválido']);
    exit;
}

// Crear CSV si no existe
if (!file_exists($csvFile)) {
    file_put_contents($csvFile,"id,name,phone,email,createdAt\n");
}

// Leer registros existentes para evitar duplicados
$rows = array_map('str_getcsv', file($csvFile));
$headers = array_shift($rows); // quitar cabecera

$duplicate = false;
foreach ($rows as $row) {
    $existingEmail = strtolower($row[3]);
    $existingPhone = preg_replace('/[\s\-]/','',$row[2]);

    if (strtolower($email) === $existingEmail || preg_replace('/[\s\-]/','',$phone) === $existingPhone){
        $duplicate = true;
        break;
    }
}

if ($duplicate){
    echo json_encode(['status'=>'error','message'=>'Ya estás registrado con ese correo o teléfono']);
    exit;
}

// Agregar nuevo registro
$id = time();
$createdAt = date('c');
$newRow = [$id,$name,$phone,$email,$createdAt];
$line = implode(',', array_map(function($v){ return '"'.str_replace('"','""',$v).'"'; }, $newRow))."\n";
file_put_contents($csvFile, $line, FILE_APPEND);

echo json_encode(['status'=>'success','message'=>'Gracias — te avisaremos cuando Amiko esté disponible.']);
