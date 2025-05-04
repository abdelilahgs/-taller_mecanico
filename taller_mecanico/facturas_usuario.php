<?php
session_start();
include 'conexion.php';  // Conexión a la base de datos

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Si no está logueado, redirigir al login
    exit();
}

$usuario_id = $_SESSION['user_id']; // Obtener el ID del usuario en sesión

// Si el formulario para actualizar una factura ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['factura_id'])) {
    $factura_id = $_POST['factura_id'];
    $nuevo_estado = $_POST['estado'];
    $nuevo_metodo_pago = $_POST['metodo_pago'];
    $nuevo_total = $_POST['total'];

    // Actualizar la factura en la base de datos
    $update_query = "UPDATE facturacion SET Estado = ?, Metodo_Pago = ?, Total = ? WHERE ID = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdi", $nuevo_estado, $nuevo_metodo_pago, $nuevo_total, $factura_id);

    if ($stmt->execute()) {
        echo '<script>alert("Factura actualizada correctamente.");</script>';
    } else {
        echo '<script>alert("Error al actualizar la factura.");</script>';
    }

    $stmt->close();
}

// Consultar las facturas del usuario en la base de datos
$query_facturas = "SELECT * FROM facturacion WHERE Usuario_ID = ?";
$stmt = $conn->prepare($query_facturas);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$facturas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
    <h2 class="text-center">📜 Mis Facturas</h2>

    <?php if (count($facturas) > 0): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Coste</th>
                    <th>Estado</th>
                    <th>Método de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura['ID']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($factura['Fecha_Emision']))) ?></td>
                        <td><?= number_format($factura['Total'], 2, ',', '.') ?> €</td>
                        <td><?= htmlspecialchars($factura['Estado']) ?></td>
                        <td><?= htmlspecialchars($factura['Metodo_Pago']) ?></td>
                        <td>
                            <!-- Formulario para editar factura -->
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $factura['ID'] ?>">Editar</button>
                        </td>
                    </tr>

                    <!-- Modal para editar factura -->
                    <div class="modal fade" id="editModal<?= $factura['ID'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Editar Factura #<?= $factura['ID'] ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para editar la factura -->
                                    <form method="POST" action="facturas_usuario.php">
                                        <input type="hidden" name="factura_id" value="<?= $factura['ID'] ?>">
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">Estado</label>
                                            <select name="estado" class="form-select">
                                                <option value="Pendiente" <?= $factura['Estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="Pagada" <?= $factura['Estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                                                <option value="Cancelada" <?= $factura['Estado'] == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="metodo_pago" class="form-label">Método de Pago</label>
                                            <input type="text" name="metodo_pago" class="form-control" value="<?= htmlspecialchars($factura['Metodo_Pago']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="total" class="form-label">Total (€)</label>
                                            <input type="number" name="total" class="form-control" value="<?= htmlspecialchars($factura['Total']) ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No tienes facturas registradas.</div>
    <?php endif; ?>
    <div class="text-center mt-4">
    <a href="dashboard_cliente.php" class="btn btn-secondary">🔙 Volver al Dashboard</a>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
