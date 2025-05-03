<?php
// Si el usuario ya está logueado, lo redirigimos al dashboard o página principal
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");  // O cualquier página a la que desees redirigir al usuario logueado
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilos para una apariencia vibrante */
        body {
            background: linear-gradient(135deg, #FF4081, #6A1B9A); /* Fondo degradado vibrante */
            font-family: 'Arial', sans-serif;
            color: #fff;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.6); /* Fondo oscuro semitransparente */
            padding: 40px;
            border-radius: 15px;
            max-width: 600px;
            margin-top: 100px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: bold;
            color: #FFEB3B; /* Color brillante para el título */
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }

        .eslogan {
            font-size: 1.2rem;
            color: #FDC787; /* Eslogan con un color brillante */
            text-align: center;
            margin-bottom: 30px;
            font-style: italic;
            font-weight: 300;
        }

        .btn {
            font-size: 1.2rem;
            padding: 15px 30px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #FF4081;
            border: none;
        }

        .btn-primary:hover {
            background-color: #F50057;
            transform: translateY(-5px); /* Hover animado */
        }

        .btn-success {
            background-color: #4CAF50;
            border: none;
        }

        .btn-success:hover {
            background-color: #388E3C;
            transform: translateY(-5px); /* Hover animado */
        }

        footer {
            margin-top: 50px;
            font-size: 1.2rem;
            color: #FFEB3B;
        }

        footer p {
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4);
        }

        /* Efectos de sombra y desplazamiento en el footer */
        footer:hover {
            transform: translateY(5px);
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .container {
                margin-top: 50px;
                padding: 30px;
            }

            h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center">Bienvenido a la Gestión de Tareas</h2>

        <!-- Eslogan debajo del título -->
        <p class="eslogan">¡Organiza tu vida, optimiza tu tiempo!</p>

        <!-- Botones para login y registro -->
        <div class="d-flex justify-content-center mt-4">
            <a href="login.php" class="btn btn-primary btn-lg mx-2">Iniciar Sesión</a>
            <a href="register.php" class="btn btn-success btn-lg mx-2">Registrarse</a>
        </div>

        <footer class="mt-5 text-center">
            <p>&copy; 2025 Gestión de Tareas</p>
        </footer>
    </div>

</body>
</html>
