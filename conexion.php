<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prueba_tecnica";

// creamos la conexion
$conn = new mysqli($servername, $username, $password, $dbname);

// verificamos la conexion
if ($conn->connect_error) {
    // en caso de error mostramos el mensaje y salimos del script con el die.
    die("Conexión fallida: " . $conn->connect_error);
}
?>
