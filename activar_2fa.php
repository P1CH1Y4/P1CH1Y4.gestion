<?php
session_start();
require 'db.php';
require_once 'PHPGangsta/GoogleAuthenticator.php'; // Librería para generar el QR

if (!isset($_GET['usuario'])) {
    die("Usuario no especificado.");
}

$usuario = $_GET['usuario'];

// Obtener el 2fa_secret del usuario
$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

if (!$fila) {
    die("Usuario no encontrado.");
}

$secret = $fila['2fa_secret'];

// Generar el código QR usando la librería de Google Authenticator
$ga = new PHPGangsta_GoogleAuthenticator();
$qrCodeUrl = $ga->getQRCodeGoogleUrl($usuario, $secret);

// Mostrar el QR y la clave secreta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Escanea el Código QR con la app Google Authenticator</h2>
    <p>Para completar la activación del 2FA, escanea el código QR a continuación con la app Google Authenticator.</p>
    <img src="<?= $qrCodeUrl ?>" alt="QR para Google Authenticator">
    <p>También puedes usar este código secreto: <strong><?= $secret ?></strong></p>
    <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
</body>
</html>
<?php
session_start();
require 'db.php';
require_once 'PHPGangsta/GoogleAuthenticator.php'; // Librería para generar el QR

if (!isset($_GET['usuario'])) {
    die("Usuario no especificado.");
}

$usuario = $_GET['usuario'];

// Obtener el 2fa_secret del usuario
$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

if (!$fila) {
    die("Usuario no encontrado.");
}

$secret = $fila['2fa_secret'];

// Generar el código QR usando la librería de Google Authenticator
$ga = new PHPGangsta_GoogleAuthenticator();
$qrCodeUrl = $ga->getQRCodeGoogleUrl($usuario, $secret);

// Mostrar el QR y la clave secreta
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activar 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Escanea el Código QR con la app Google Authenticator</h2>
    <p>Para completar la activación del 2FA, escanea el código QR a continuación con la app Google Authenticator.</p>
    <img src="<?= $qrCodeUrl ?>" alt="QR para Google Authenticator">
    <p>También puedes usar este código secreto: <strong><?= $secret ?></strong></p>
    <a href="login.php" class="btn btn-primary">Iniciar sesión</a>
</body>
</html>
