<?php
session_start();
require '../db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar que el parámetro ID esté presente en la URL
if (isset($_GET['id'])) {
    $tarea_id = $_GET['id'];

    // Obtener la tarea de la base de datos
    $sql = "SELECT * FROM tareas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si existe la tarea, cargar los datos en el formulario
    if ($resultado->num_rows > 0) {
        $tarea = $resultado->fetch_assoc();
    } else {
        $_SESSION['mensaje'] = "Tarea no encontrada o no pertenece a tu cuenta.";
        header("Location: ../dashboard.php");
        exit;
    }
} else {
    $_SESSION['mensaje'] = "No se proporcionó el ID de la tarea.";
    header("Location: ../dashboard.php");
    exit;
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    // Actualizar la tarea en la base de datos
    if (empty(trim($titulo)) || empty(trim($descripcion))) {
    $_SESSION['mensaje'] = "Título y descripción no pueden estar vacíos.";
    } else {
        $sql = "UPDATE tareas SET titulo = ?, descripcion = ? WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $titulo, $descripcion, $tarea_id, $usuario_id);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Tarea actualizada correctamente.";
            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['mensaje'] = "Hubo un error al actualizar la tarea.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2>Editar Tarea</h2>

    <!-- Mensajes de error o éxito -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-info"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <!-- Formulario de edición de tarea -->
    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($tarea['titulo'], ENT_QUOTES); ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($tarea['descripcion']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
    </form>

    <hr>
    <a href="../dashboard.php" class="btn btn-secondary">Cancelar</a>

</body>
</html>
