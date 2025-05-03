<?php


$servername = "sql312.infinityfree.com";  // Este es el servidor MySQL proporcionado por InfinityFree
$username = "if0_38879801";               // Tu nombre de usuario de base de datos
$password = "GzbNNUessjvt";                 // Tu contraseña de base de datos
$dbname = "if0_38879801_db_gestion";  // El nombre de tu base de datos


// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
