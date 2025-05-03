<?php
session_start();
require '../db.php';

// Si no está logueado, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $usuario_id = $_SESSION['usuario_id'];

    // Validar que los campos no estén vacíos
    if (empty($titulo) || empty($descripcion)) {
        $mensaje = "Todos los campos son obligatorios.";
    } else {
        // Insertar la tarea en la base de datos
        $sql = "INSERT INTO tareas (titulo, descripcion, usuario_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $descripcion, $usuario_id);

        if ($stmt->execute()) {
            // Si la tarea se insertó correctamente, redirigir al dashboard
            header("Location: ../index.php");
            exit;
        } else {
            $mensaje = "Error al crear la tarea: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Crear Nueva Tarea</h2>

    <!-- Mensaje de error o éxito -->
    <?php if ($mensaje): ?>
        <div class="alert alert-warning"><?= $mensaje ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Crear Tarea</button>
    </form>

    <p class="mt-3"><a href="../index.php">Volver al Dashboard</a></p>

</body>
</html>

