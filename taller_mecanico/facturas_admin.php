<?php
include 'conexion.php'; // Conexión a la base de datos
session_start(); // Asegurar que la sesión está iniciada

$mensaje = ''; // Variable para almacenar el mensaje de alerta

// Verificar si se envió un formulario para actualizar el estado de una factura
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['factura_id']) && isset($_POST['estado'])) {
    $factura_id = $_POST['factura_id'];
    $nuevo_estado = $_POST['estado'];

    // Actualizar el estado de la factura en la base de datos
    $update_query = "UPDATE facturacion SET Estado = ? WHERE ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $nuevo_estado, $factura_id);

    if ($stmt->execute()) {
        // Mensaje de éxito con SweetAlert
        $mensaje = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Éxito!",
                            text: "El estado de la factura se ha actualizado correctamente.",
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
                            text: "No se pudo actualizar el estado de la factura.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    }
    $stmt->close();
}

// Verificar si se envió una solicitud para eliminar una factura
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar_factura_id'])) {
    $factura_id = $_POST['eliminar_factura_id'];

    // Eliminar la factura de la base de datos
    $delete_query = "DELETE FROM facturacion WHERE ID = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $factura_id);

    if ($stmt->execute()) {
        // Mensaje de éxito con SweetAlert
        $mensaje = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "¡Eliminado!",
                            text: "La factura se ha eliminado correctamente.",
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
                            text: "No se pudo eliminar la factura.",
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>';
    }
    $stmt->close();
}

// Consultar todas las facturas desde la base de datos
$query_facturas = "SELECT * FROM facturacion"; 
$result_facturas = mysqli_query($conn, $query_facturas);

// Verificar si la consulta se ejecutó correctamente
if (!$result_facturas) {
    echo "Error al obtener las facturas: " . mysqli_error($conn);
    exit();
}

// Recoger las facturas
$facturas = [];
while ($row = mysqli_fetch_assoc($result_facturas)) {
    $facturas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Facturas</title>
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

<!-- CONTENIDO -->
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
    </div>
    
    <h2 class="text-center">📜 Gestión de Facturas</h2>

    <!-- Mostrar el mensaje de alerta si existe -->
    <?php if ($mensaje): ?>
        <?= $mensaje ?>  <!-- Esto ejecutará la alerta con SweetAlert -->
    <?php endif; ?>

    <!-- Mostrar Facturas -->
    <h3 class="text-center mt-5">Lista de Facturas</h3>
    <?php if (!empty($facturas)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Reparación</th>
                    <th>Fecha</th>
                    <th>Total (€)</th>
                    <th>Estado</th>
                    <th>Método de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura['Usuario_ID']) ?></td>
                        <td><?= htmlspecialchars($factura['Reparacion_ID']) ?></td>
                        <td>
                            <?= isset($factura['Fecha_Emision']) && !empty($factura['Fecha_Emision']) 
                                ? date('d/m/Y', strtotime($factura['Fecha_Emision'])) 
                                : 'Fecha no disponible' ?>
                        </td>
                        <td><?= number_format($factura['Total'], 2, ',', '.') ?> €</td>
                        <td>
                            <span class="badge bg-<?= $factura['Estado'] == 'Pendiente' ? 'warning' : ($factura['Estado'] == 'Pagada' ? 'success' : 'danger') ?>">
                                <?= htmlspecialchars($factura['Estado']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($factura['Metodo_Pago']) ?></td>
                        <td>
                            <!-- Formulario para cambiar el estado de la factura -->
                            <form method="POST" action="facturas_admin.php" style="display:inline;">
                                <input type="hidden" name="factura_id" value="<?= $factura['ID'] ?>">
                                <select name="estado" class="form-select">
                                    <option value="Pendiente" <?= $factura['Estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="Pagada" <?= $factura['Estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                                    <option value="Cancelada" <?= $factura['Estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                </select>
                                <button type="submit" class="btn btn-info btn-sm mt-2">Actualizar</button>
                            </form>

                            <!-- Formulario para eliminar la factura -->
                            <form method="POST" action="facturas_admin.php" style="display:inline;">
                                <input type="hidden" name="eliminar_factura_id" value="<?= $factura['ID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('¿Estás seguro de que quieres eliminar esta factura?');">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No se encontraron facturas.</div>
    <?php endif; ?>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
