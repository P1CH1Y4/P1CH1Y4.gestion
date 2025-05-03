<?php
session_start();
require_once 'PHPGangsta/GoogleAuthenticator.php';

$mensaje = '';

if (!isset($_SESSION['2fa_temp'])) {
    header("Location: login.php");
    exit;
}

// Procesar el código del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_usuario = $_POST['codigo'];
    $ga = new PHPGangsta_GoogleAuthenticator();
    $codigo_valido = $ga->verifyCode($_SESSION['2fa_temp']['secret'], $codigo_usuario, 2); // 2 = tolerancia de 2 * 30s

    if ($codigo_valido) {
        // Código correcto, iniciar sesión
        $_SESSION['usuario_id'] = $_SESSION['2fa_temp']['id'];
        $_SESSION['usuario'] = $_SESSION['2fa_temp']['usuario'];
        unset($_SESSION['2fa_temp']); // Limpiar temporal
        header("Location: dashboard.php");
        exit;
    } else {
        $mensaje = "Código incorrecto. Intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación 2FA</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
      crossorigin="anonymous"
    />
</head>
<body class="bg-info d-flex justify-content-center align-items-center vh-100">
    <div
      class="bg-white p-5 rounded-5 text-secondary shadow"
      style="width: 25rem"
    >
      <div class="d-flex justify-content-center">
        <img
          src="assets/code.svg"
          alt="code"
          style="height: 7rem"
        />
      </div>
      <div class="text-center fs-1 fw-bold">Verificación 2FA</div>

      <?php if ($mensaje): ?>
        <div class="alert alert-danger text-center mt-3"><?= $mensaje ?></div>
      <?php endif; ?>

      <form method="POST" class="mt-4">
        <!-- Campo para el código del autenticador -->
        <div class="input-group">
          <div class="input-group-text bg-info">
            <img
              src="assets/code.svg"
              alt="code"
              style="height: 1rem"
            />
          </div>
          <input
            class="form-control bg-light"
            type="text"
            name="codigo"
            id="codigo"
            placeholder="Código del autenticador"
            required
          />
        </div>

        <!-- Botón para verificar -->
        <button type="submit" class="btn btn-info text-white w-100 mt-4 fw-semibold shadow-sm">
          Verificar
        </button>
      </form>
    </div>
</body>
</html>