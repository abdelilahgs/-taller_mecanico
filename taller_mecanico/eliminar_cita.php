<?php
include 'conexion.php';  // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cita_id'])) {
    $cita_id = $_POST['cita_id'];

    $sql = "DELETE FROM Citas WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cita_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit;
}
?>
