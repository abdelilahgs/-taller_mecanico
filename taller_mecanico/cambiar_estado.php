<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Solo admins pueden cambiar estado
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(["success" => false, "message" => "No autorizado."]);
    exit();
}

// Validar entrada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? null;
    $estado = $_POST['estado'] ?? null;

    // Define una lista de estados permitidos: pendiente, aprobado, bloqueado, denegado
    $estados_validos = ['pendiente', 'aprobado', 'bloqueado', 'denegado'];
    // Comprueba si la variable $estado está en la lista de estados válidos
    if (!in_array($estado, $estados_validos)) {
        // Si $estado no es válido, devuelve un mensaje de error en formato JSON y detiene el código.
        echo json_encode(["success" => false, "message" => "Estado no válido."]);
        exit();
    }

    // Prepara una consulta SQL para actualizar el campo 'estado' de la tabla 'usuarios' donde el 'id' coincida con un valor dado.
    $stmt = $conn->prepare("UPDATE usuarios SET estado = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $estado, $user_id); // Asocia los valores de $estado (string) y $user_id (integer) a los marcadores '?' de la consulta.
            // Ejecuta la consulta.
        if ($stmt->execute()) {
            // Si la ejecución es exitosa, devuelve un mensaje JSON indicando éxito.
            echo json_encode(["success" => true]);
        } else {
            // Si falla la ejecución, envía un mensaje JSON con el error.
            echo json_encode(["success" => false, "message" => "No se pudo actualizar."]);
        }
            // Cierra la consulta preparada.
            $stmt->close();
    } else {
        // Si falla la preparación de la consulta, envía un mensaje JSON con el error.
        echo json_encode(["success" => false, "message" => "Error en la preparación de la consulta."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
