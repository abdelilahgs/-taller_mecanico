<?php

$host = "localhost";
$user = "root";  
$pass = "";      
$dbname = "taller_mecanico";  // Nombre de la base de datos

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>