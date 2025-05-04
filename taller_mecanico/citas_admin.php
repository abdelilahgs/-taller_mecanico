<?php
include 'conexion.php'; // Conexión a la base de datos

$mensaje = '';

// Actualizar estado de cita
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['cita_id']) && isset($_POST['estado'])) {
        $cita_id = $_POST['cita_id'];
        $nuevo_estado = $_POST['estado'];

        $stmt = $conn->prepare("UPDATE Citas SET Estado = ? WHERE ID = ?");
        $stmt->bind_param("si", $nuevo_estado, $cita_id);

        // Mensaje de exito
        if ($stmt->execute()) {
            $mensaje = '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Éxito!",
                    text: "El estado de la cita se ha actualizado correctamente.",
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>';
        } else {
            // Mensaje de error
            $mensaje = '<script>
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se pudo actualizar el estado de la cita.",
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>';
        }
        $stmt->close();
    }

    // Eliminar cita
    if (isset($_POST['eliminar_id'])) {
        $eliminar_id = $_POST['eliminar_id'];

        $stmt = $conn->prepare("DELETE FROM Citas WHERE ID = ?");
        $stmt->bind_param("i", $eliminar_id);

        // Mensaje de eliminado correctamente
        if ($stmt->execute()) {
            $mensaje = '<script>
                Swal.fire({
                    icon: "success",
                    title: "¡Eliminado!",
                    text: "La cita ha sido eliminada correctamente.",
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>';
        } else {
            // Mensaje de error al eliminar la cita
            $mensaje = '<script>
                Swal.fire({
                    icon: "error",
                    title: "¡Error!",
                    text: "No se pudo eliminar la cita.",
                    timer: 3000,
                    showConfirmButton: false
                });
            </script>';
        }
        $stmt->close();
    }
}

// Obtener todas las citas
$query = "SELECT * FROM Citas";
$result = $conn->query($query);
$citas = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Citas</title>
    <link rel="icon" href="img/favicon.png" type="img/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">🏠 Taller Mecánico</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="reparaciones.php">🔧 Reparaciones</a></li>
                <li class="nav-item"><a class="nav-link" href="citas.php">📅 Citas</a></li>
                <li class="nav-item"><a class="nav-link" href="facturacion.php">🧾 Facturación</a></li>
                <li class="nav-item"><a class="nav-link" href="vehiculos.php">🚗 Vehículos</a></li>
                <li class="nav-item ms-3"><a href="logout.php" class="btn btn-danger">🚪 Cerrar Sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
    </div>

    <h2 class="text-center">📅 Lista de Citas</h2>
    <br>

    <?= $mensaje ?>

    <?php if (!empty($citas)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($cita['Fecha_Cita'])) ?></td>
                        <td><?= htmlspecialchars($cita['Usuario_ID']) ?></td>
                        <td>
                            <span class="badge bg-<?= match($cita['Estado']) {
                                'Confirmada' => 'success',
                                'Pendiente' => 'warning',
                                'Completada' => 'primary',
                                default => 'danger'
                            } ?>">
                                <?= htmlspecialchars($cita['Estado']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <!-- Actualizar estado -->
                                <form method="POST" action="citas_admin.php" class="w-100">
                                    <input type="hidden" name="cita_id" value="<?= $cita['ID'] ?>">
                                    <div class="input-group input-group-sm">
                                        <select name="estado" class="form-select">
                                            <?php foreach (['Pendiente', 'Confirmada', 'Completada', 'Cancelada'] as $estado): ?>
                                                <option value="<?= $estado ?>" <?= $cita['Estado'] === $estado ? 'selected' : '' ?>>
                                                    <?= $estado ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary">Actualizar</button>
                                    </div>
                                </form>

                                <!-- Eliminar cita -->
                                <form method="POST" action="citas_admin.php" class="w-100"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                    <input type="hidden" name="eliminar_id" value="<?= $cita['ID'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">🗑 Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning text-center">⚠️ No se encontraron citas.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
