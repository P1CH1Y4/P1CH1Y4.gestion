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

    // Comprobar si la tarea pertenece al usuario
    $sql = "SELECT * FROM tareas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $tarea_id, $usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si existe la tarea, proceder a eliminarla
    if ($resultado->num_rows > 0) {
        $sql = "DELETE FROM tareas WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $tarea_id, $usuario_id);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Tarea eliminada correctamente.";
        } else {
            $_SESSION['mensaje'] = "Hubo un error al eliminar la tarea.";
        }
    } else {
        $_SESSION['mensaje'] = "Tarea no encontrada o no pertenece a tu cuenta.";
    }
} else {
    $_SESSION['mensaje'] = "No se proporcionó el ID de la tarea.";
}

// Redirigir de vuelta al dashboard
header("Location: ../dashboard.php");
exit;
