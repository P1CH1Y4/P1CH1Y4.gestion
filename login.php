<?php
session_start();
require 'db.php'; // Conexión a base de datos
require_once 'PHPGangsta/GoogleAuthenticator.php'; // Librería para 2FA

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar reCAPTCHA
    $recaptcha_secret = "6LdKTSsrAAAAAImGS4Lc4e96yvhpFDVNiEf8TcVy";
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if ($response_keys["success"]) {
        $nombre = $_POST['nombre'];
        $contrasena = $_POST['contrasena'];

        // Buscar usuario
        $sql = "SELECT * FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();

            if (password_verify($contrasena, $fila['contrasena'])) {
                if (!empty($fila['2fa_secret'])) {
                    // Usuario con 2FA activado → guardar temporal y redirigir
                    $_SESSION['2fa_temp'] = [
                        'id' => $fila['id'],
                        'usuario' => $fila['usuario'],
                        'secret' => $fila['2fa_secret']
                    ];
                    header("Location: verificar_2fa.php");
                    exit;
                } else {
                    // Sin 2FA → iniciar sesión directamente
                    $_SESSION['usuario_id'] = $fila['id'];
                    $_SESSION['usuario'] = $fila['usuario'];
                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            $mensaje = "Usuario no encontrado.";
        }
    } else {
        $mensaje = "Verificación de Captcha fallida. Intenta nuevamente.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/x-icon" href="assets/logo-vt.svg" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap Login Page</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- ReCAPTCHA -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx"
      crossorigin="anonymous"
    />
  </head>
  <body class="bg-info d-flex justify-content-center align-items-center vh-100">
    <!-- Mostrar mensaje de error si existe -->
    <?php if ($mensaje): ?>
      <div class="alert alert-danger"><?= $mensaje ?></div>
    <?php endif; ?>

    <div
      class="bg-white p-5 rounded-5 text-secondary shadow"
      style="width: 25rem"
    >
      <div class="d-flex justify-content-center">
        <img
          src="assets/login-icon.svg"
          alt="login-icon"
          style="height: 7rem"
        />
      </div>
      <div class="text-center fs-1 fw-bold">Login</div>

      <form method="POST">
        <!-- Campo para el nombre de usuario -->
        <div class="input-group mt-4">
          <div class="input-group-text bg-info">
            <img
              src="assets/username-icon.svg"
              alt="username-icon"
              style="height: 1rem"
            />
          </div>
          <input
            class="form-control bg-light"
            type="text"
            name="nombre"
            id="nombre"
            placeholder="Nombre de Usuario"
            required
          />
        </div>

        <!-- Campo para la contraseña -->
        <div class="input-group mt-3">
          <div class="input-group-text bg-info">
            <img
              src="assets/password-icon.svg"
              alt="password-icon"
              style="height: 1rem"
            />
          </div>
          <input
            class="form-control bg-light"
            type="password"
            name="contrasena"
            id="contrasena"
            placeholder="Contraseña"
            required
          />
        </div>

        <!-- ReCAPTCHA -->
        <div class="g-recaptcha mt-3" data-sitekey="6LdKTSsrAAAAAMl0KgDpCDgTChkBZgPm-CqgIN__"></div>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="btn btn-info text-white w-100 mt-4 fw-semibold shadow-sm">
          Entrar
        </button>
      </form>

      <!-- Enlace a la página de registro si no tienen cuenta -->
      <div class="d-flex gap-1 justify-content-center mt-3">
        <div>¿No tienes una cuenta?</div>
        <a href="register.php" class="text-decoration-none text-info fw-semibold"
          >Registrarse</a
        >
      </div>

      <!-- Opcional: Enlace para recuperación de contraseña -->
      <div class="d-flex justify-content-center mt-2">
        <a
          href="forgot_password.php"
          class="text-decoration-none text-info fw-semibold fst-italic"
          style="font-size: 0.9rem"
          >¿Olvidaste tu contraseña?</a
        >
      </div>
    </div>
  </body>
</html>
