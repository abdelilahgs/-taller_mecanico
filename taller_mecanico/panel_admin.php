<?php
require 'conexion.php';

// Obtener usuarios pendientes
$sql = "SELECT * FROM usuarios WHERE estado = 'pendiente'";
$result = $conn->query($sql);

echo "<h2>Usuarios Pendientes</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<p>{$row['nombre']} - {$row['email']} 
          <a href='aprobar_usuario.php?id={$row['ID']}' class='btn btn-success'>Aprobar</a></p>";
}
?>
