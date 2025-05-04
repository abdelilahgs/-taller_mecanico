<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Aprobar al usuario
    $query = "UPDATE usuarios SET status = 'aprobado' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header("Location: dashboard_admin.php"); // Redirigir al panel después de aprobar
}
?>
