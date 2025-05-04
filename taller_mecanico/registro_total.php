<?php
include 'config.php';

function registrarAccion($usuario_id, $tipo_registro, $detalles) {
    global $conn;
    $query = "INSERT INTO registro_total (usuario_id, tipo_registro, detalles) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $usuario_id, $tipo_registro, $detalles);
    $stmt->execute();
    $stmt->close();
}
?>