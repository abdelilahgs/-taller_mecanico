<?php
include 'conexion.php'; // Conexión a la base de datos

$mensaje = ''; // Variable para almacenar el mensaje de alerta

// Verificar si se envió un formulario para actualizar el estado de una reparación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reparacion_id']) && isset($_POST['estado'])) {
    $reparacion_id = $_POST['reparacion_id'];
    $nuevo_estado = $_POST['estado'];

    // Actualizar el estado de la reparación en la base de datos
    $update_query = "UPDATE reparaciones SET Estado = ? WHERE ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $nuevo_estado, $reparacion_id);

    if ($stmt->execute()) {
        $mensaje = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "El estado de la reparación se ha actualizado correctamente.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    } else {
        $mensaje = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "No se pudo actualizar el estado de la reparación.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    }
    $stmt->close();
}

// ✅ CONSULTA SIN FECHA_SALIDA NI PIEZAS_UTILIZADAS
$query_reparaciones = "SELECT r.*, 
                              u.username AS Nombre_Mecanico 
                       FROM reparaciones r
                       LEFT JOIN usuarios u ON r.Mecanico_ID = u.ID"; 

$result_reparaciones = mysqli_query($conn, $query_reparaciones);

if (!$result_reparaciones) {
    die("Error al obtener las reparaciones: " . mysqli_error($conn));
}

$reparaciones = [];
while ($row = mysqli_fetch_assoc($result_reparaciones)) {
    $reparaciones[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reparaciones</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
    </div>

    <h2 class="text-center">🛠️ Gestión de Reparaciones</h2>

    <?php if ($mensaje): ?>
        <?= $mensaje ?>
    <?php endif; ?>

    <h3 class="text-center mt-5">Lista de Reparaciones</h3>
    <?php if (!empty($reparaciones)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Mecánico</th>
                    <th>Descripción</th>
                    <th>Costo (€)</th>
                    <th>Estado</th>
                    <th>Ingreso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reparaciones as $reparacion): ?>
                    <tr>
                        <td><?= htmlspecialchars($reparacion['ID']) ?></td>
                        <td><?= htmlspecialchars($reparacion['Nombre_Mecanico'] ?? 'No asignado') ?></td>
                        <td><?= htmlspecialchars($reparacion['Descripcion']) ?></td>
                        <td><?= number_format($reparacion['Costo'], 2, ',', '.') ?> €</td>
                        <td>
                            <span class="badge bg-<?= $reparacion['Estado'] == 'Pendiente' ? 'warning' : ($reparacion['Estado'] == 'Completado' ? 'success' : 'info') ?>">
                                <?= htmlspecialchars($reparacion['Estado']) ?>
                            </span>
                        </td>
                        <td><?= $reparacion['Fecha_Ingreso'] ?? 'No disponible' ?></td>
                        <td>
                            <form method="POST" action="reparacion_admin.php" style="display:inline;">
                                <input type="hidden" name="reparacion_id" value="<?= $reparacion['ID'] ?>">
                                <select name="estado" class="form-select">
                                    <option value="Pendiente" <?= $reparacion['Estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="En Proceso" <?= $reparacion['Estado'] == 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                                    <option value="Completado" <?= $reparacion['Estado'] == 'Completado' ? 'selected' : '' ?>>Completado</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm mt-2">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No se encontraron reparaciones.</div>
    <?php endif; ?>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
