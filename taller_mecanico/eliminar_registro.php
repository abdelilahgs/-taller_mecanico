<?php
include 'conexion.php';  // Conexión a la base de datos
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo json_encode(["success" => false, "error" => "Acceso denegado."]);
    exit;
}

// Verificar que se reciban los datos necesarios
if (isset($_POST['id'], $_POST['tabla'])) {
    $id = intval($_POST['id']);
    $tabla = preg_replace('/[^a-zA-Z_]/', '', $_POST['tabla']); // Filtrar el nombre de la tabla

    // Evitar SQL Injection asegurando que la tabla es válida
    $tablas_validas = ['Reparaciones', 'Citas', 'Facturacion', 'Vehiculos'];
    if (!in_array($tabla, $tablas_validas)) {
        echo json_encode(["success" => false, "error" => "Tabla no válida."]);
        exit;
    }

    // Ejecutar la eliminación
    $sql = "DELETE FROM $tabla WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo eliminar el registro."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Datos insuficientes."]);
}

$conn->close();
?>
