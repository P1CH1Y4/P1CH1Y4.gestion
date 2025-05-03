<?php
session_start();
require 'db.php'; // Asegúrate de tener la conexión a la base de datos
require_once 'PHPGangsta/GoogleAuthenticator.php'; // Librería para generar el secreto 2FA

$mensaje = ''; // Variable para los mensajes de error o éxito

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verificar reCAPTCHA
    $recaptcha_secret = "6LdKTSsrAAAAAImGS4Lc4e96yvhpFDVNiEf8TcVy"; // Sustituye con tu clave secreta de reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if ($response_keys["success"]) {
        // Validar que las contraseñas coincidan
        if ($contrasena !== $confirmar_contrasena) {
            $mensaje = "Las contraseñas no coinciden.";
        } else {
            // Validar que no exista ya el nombre de usuario o correo
            $sql = "SELECT * FROM usuarios WHERE usuario = ? OR correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nombre, $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $mensaje = "El nombre de usuario o el correo ya están en uso.";
            } else {
                // Generar un secreto 2FA con la librería de GoogleAuthenticator
                $ga = new PHPGangsta_GoogleAuthenticator();
                $secret = $ga->createSecret(); // El secreto que luego se usará para generar el código

                // Encriptar la contraseña
                $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

                // Guardar los datos en la base de datos (incluyendo el secreto 2FA)
                $sql = "INSERT INTO usuarios (usuario, correo, contrasena, 2fa_secret) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $nombre, $correo, $hashed_password, $secret);
                $stmt->execute();

                // Redirigir al usuario a una página donde puedan ver su código QR para la app de Google Authenticator
                $_SESSION['usuario'] = $nombre;
                $_SESSION['2fa_secret'] = $secret;
                header("Location: activar_2fa.php?usuario=" . urlencode($nombre));
                exit;
            }
        }
    } else {
        $mensaje = "Verificación de Captcha fallida. Intenta nuevamente.";
    }
}
?>

<!-- filepath: c:\Users\elmer\Downloads\login-page-bootstrap-main\register.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <!-- Mostrar mensaje de error si existe -->
        <?php if ($mensaje): ?>
            <div class="alert alert-danger"><?= $mensaje ?></div>
        <?php endif; ?>


      <div class="d-flex justify-content-center">
        <img
          src="assets/online-registration.svg"
          alt="online-registration"
          style="height: 7rem"
        />
      </div>
      <div class="text-center fs-1 fw-bold">Registro</div>

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

        <!-- Campo para el correo electrónico -->
        <div class="input-group mt-3">
          <div class="input-group-text bg-info">
            <img
              src="assets/username-icon.svg"
              alt="username-icon"
              style="height: 1rem"
            />
          </div>
          <input
            class="form-control bg-light"
            type="email"
            name="correo"
            id="correo"
            placeholder="Correo Electrónico"
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

        <!-- Campo para confirmar la contraseña -->
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
            name="confirmar_contrasena"
            id="confirmar_contrasena"
            placeholder="Confirmar Contraseña"
            required
          />
        </div>

        <!-- ReCAPTCHA -->
        <div class="g-recaptcha mt-3" data-sitekey="6LdKTSsrAAAAAMl0KgDpCDgTChkBZgPm-CqgIN__"></div>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="btn btn-info text-white w-100 mt-4 fw-semibold shadow-sm">
          Registrarse
        </button>
      </form>

      <!-- Enlace para inicio de sesión -->
      <div class="d-flex gap-1 justify-content-center mt-3">
        <div>¿Ya tienes una cuenta?</div>
        <a href="login.php" class="text-decoration-none text-info fw-semibold"
          >Iniciar sesión</a
        >
      </div>
    </div>
</body>
</html>