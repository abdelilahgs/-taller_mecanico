<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si se ha recibido un ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el registro directamente (de la tabla 'reparaciones')
    $sql = "DELETE FROM reparaciones WHERE ID = ?"; // Eliminar de la tabla 'reparaciones' por defecto
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);  // "i" para enteros

    if ($stmt->execute()) {
        // Si se elimina correctamente, devolver éxito
        echo json_encode(['status' => 'success']);
    } else {
        // Si hubo un error al eliminar, devolver error
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el registro. Intenta de nuevo.']);
    }
} else {
    // Si no se recibió el ID
    echo json_encode(['status' => 'error', 'message' => 'No se recibió el ID para eliminar.']);
}

// Cerrar la conexión
$conn->close();
?>
