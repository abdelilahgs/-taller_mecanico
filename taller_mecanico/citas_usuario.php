<?php
session_start();
include 'conexion.php'; // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // ID del usuario logueado

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener las citas del cliente logueado
$query = "SELECT * FROM citas WHERE Usuario_ID = ?";
$stmt = $conn->prepare($query);

// Comprobar si la consulta se preparó correctamente
if ($stmt === false) {
    die('Error en la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $user_id); // $user_id es el ID del cliente logueado
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se obtuvieron resultados
if ($result->num_rows == 0) {
    $no_citas_message = "No tienes citas registradas.";
}

// Editar cita
if (isset($_GET['edit'])) {
    $cita_id = $_GET['edit'];

    // Obtener los detalles de la cita para editarla
    $edit_query = "SELECT * FROM citas WHERE id = ? AND Usuario_ID = ?";
    $edit_stmt = $conn->prepare($edit_query);
    
    if ($edit_stmt === false) {
        die('Error en la consulta de edición: ' . $conn->error);
    }

    $edit_stmt->bind_param("ii", $cita_id, $user_id); // Cliente = $user_id
    $edit_stmt->execute();
    $edit_result = $edit_stmt->get_result();
    $edit_cita = $edit_result->fetch_assoc();

    // Si no se encuentra la cita, redirigir
    if (!$edit_cita) {
        header("Location: citas_usuario.php");
        exit();
    }

    // Lógica para actualizar la cita
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $descripcion = $_POST['descripcion'];

        $update_query = "UPDATE citas SET fecha = ?, hora = ?, descripcion = ? WHERE id = ? AND Usuario_ID = ?";
        $update_stmt = $conn->prepare($update_query);
        
        if ($update_stmt === false) {
            die('Error en la consulta de actualización: ' . $conn->error);
        }

        $update_stmt->bind_param("ssssi", $fecha, $hora, $descripcion, $cita_id, $user_id);
        if ($update_stmt->execute()) {
            header("Location: citas_usuario.php");
            exit();
        } else {
            $error_message = "Error al actualizar la cita.";
        }
    }
}

// Eliminar cita
if (isset($_GET['delete'])) {
    $cita_id = $_GET['delete'];

    $delete_query = "DELETE FROM citas WHERE id = ? AND Usuario_ID = ?";
    $delete_stmt = $conn->prepare($delete_query);
    
    if ($delete_stmt === false) {
        die('Error en la consulta de eliminación: ' . $conn->error);
    }

    $delete_stmt->bind_param("ii", $cita_id, $user_id); // Cliente = $user_id
    if ($delete_stmt->execute()) {
        header("Location: citas_usuario.php");
        exit();
    } else {
        $error_message = "Error al eliminar la cita.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Citas</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">🏠 Taller Mecánico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="reparaciones.php">🔧 Reparaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="citas.php">📅 Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="facturacion.php">🧾 Facturación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vehiculos.php">🚗 Vehículos</a>
                </li>
                <!-- Botón de Cerrar Sesión -->
                <li class="nav-item ms-3">
                    <a href="logout.php" class="btn btn-danger">🚪 Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Mis Citas</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if (isset($no_citas_message)): ?>
        <div class="alert alert-info"><?= $no_citas_message ?></div>
    <?php endif; ?>

    <!-- Botón Atrás -->
    <a href="dashboard_cliente.php" class="btn btn-secondary mb-3">← Volver al Dashboard</a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php include 'footer.php'; ?>

</html>

