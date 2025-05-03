<?php
session_start();  // Inicia la sesión

// Destruye todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

// Redirige al usuario a la página de login
header("Location: login.php");
exit;
?>

