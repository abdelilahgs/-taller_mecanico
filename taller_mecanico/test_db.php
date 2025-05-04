<?php
// Incluir la configuración de la base de datos
include 'config.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
} else {
    echo "✅ Conexión exitosa a la base de datos.";
}
?>
