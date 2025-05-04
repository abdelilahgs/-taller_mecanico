<?php
include 'conexion.php'; // Conexión a la base de datos

$mensaje = ''; // Variable para almacenar el mensaje de alerta

// Verificar si se envió un formulario para actualizar el estado de un vehículo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['vehiculo_id']) && isset($_POST['estado'])) {
    $vehiculo_id = $_POST['vehiculo_id'];
    $nuevo_estado = $_POST['estado'];

    // Actualizar el estado del vehículo en la base de datos
    $update_query = "UPDATE vehiculos SET Estado = ? WHERE ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $nuevo_estado, $vehiculo_id);

    if ($stmt->execute()) {
        // Mensaje de éxito con SweetAlert
        $mensaje = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "El estado del vehículo se ha actualizado correctamente.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    } else {
        // Mensaje de error con SweetAlert
        $mensaje = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "¡Error!",
                            text: "No se pudo actualizar el estado del vehículo.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    }
    $stmt->close();
}

// Consultar todos los vehículos desde la base de datos
$query_vehiculos = "SELECT * FROM vehiculos";
$result_vehiculos = mysqli_query($conn, $query_vehiculos);

// Verificar si la consulta se ejecutó correctamente
if (!$result_vehiculos) {
    echo "Error al obtener los vehículos: " . mysqli_error($conn);
    exit();
}

// Recoger los vehículos
$vehiculos = [];
while ($row = mysqli_fetch_assoc($result_vehiculos)) {
    $vehiculos[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehículos</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- SweetAlert2 -->
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

    <h2 class="text-center">🚗 Gestión de Vehículos</h2>

    <!-- Mostrar el mensaje de alerta si existe -->
    <?php if ($mensaje): ?>
        <?= $mensaje ?>  <!-- Esto ejecutará la alerta con SweetAlert -->
    <?php endif; ?>

    <!-- Mostrar Vehículos -->
    <h3 class="text-center mt-5">Lista de Vehículos</h3>
    <?php if (!empty($vehiculos)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Matrícula</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehiculos as $vehiculo): ?>
                    <tr>
                        <td><?= htmlspecialchars($vehiculo['ID']) ?></td>
                        <td><?= htmlspecialchars($vehiculo['Marca']) ?></td>
                        <td><?= htmlspecialchars($vehiculo['Modelo']) ?></td>
                        <td><?= htmlspecialchars($vehiculo['Año']) ?></td>
                        <td><?= htmlspecialchars($vehiculo['Matricula']) ?></td>
                        <td>
                            <span class="badge bg-<?= $vehiculo['Estado'] == 'Activo' ? 'success' : 'secondary' ?>">
                                <?= htmlspecialchars($vehiculo['Estado']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($vehiculo['Fecha_Registro'])) ?></td>
                        <td>
                            <!-- Formulario para cambiar el estado del vehículo -->
                            <form method="POST" action="vehiculos_admin.php" style="display:inline;">
                                <input type="hidden" name="vehiculo_id" value="<?= $vehiculo['ID'] ?>">
                                <select name="estado" class="form-select">
                                    <option value="Activo" <?= $vehiculo['Estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                    <option value="Inactivo" <?= $vehiculo['Estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm mt-2">Actualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No se encontraron vehículos.</div>
    <?php endif; ?>
</div>
</body>
</html>

<?php include 'footer.php'; ?>
