<?php 
include 'config.inc.php'; 
include 'header.php'; 

$facturas = [];

// Si se recibe un usuario, buscar facturas de ese usuario
if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];

    // Preparar la consulta para obtener las facturas del usuario
    $stmt = $conn->prepare("SELECT ID, Usuario_ID, Reparacion_ID, Fecha_Emision, Total, Estado, Metodo_Pago FROM Facturacion WHERE Usuario_ID = ?");
    if ($stmt === false) {
        echo "<div class='alert alert-danger text-center'>❌ Error al preparar la consulta: " . $conn->error . "</div>";
        exit;
    }
    $stmt->bind_param("s", $usuario_id);  // Cambiar "i" por "s" para usar cadena
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $facturas[] = $row;
    }

    $stmt->close();
}

// Procesar la factura cuando se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_nombre = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : NULL;
    $reparacion_id = !empty($_POST['reparacion_id']) ? $_POST['reparacion_id'] : NULL;
    $total = $_POST['total'];
    $estado = $_POST['estado'];
    $metodo_pago = $_POST['metodo_pago'];
    $fecha_emision = date("Y-m-d");

    // Verificar que el nombre del cliente no esté vacío
    if ($usuario_nombre === NULL) {
        echo "<div class='alert alert-danger text-center'>❌ El campo de nombre del cliente no puede estar vacío.</div>";
        exit;
    }

    // Preparar la consulta SQL para insertar la factura
    $stmt = $conn->prepare("INSERT INTO Facturacion (Usuario_ID, Reparacion_ID, Fecha_Emision, Total, Estado, Metodo_Pago) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "<div class='alert alert-danger text-center'>❌ Error al preparar la consulta de inserción: " . $conn->error . "</div>";
        exit;
    }

    // Insertar los datos de la factura en la base de datos
    $stmt->bind_param("ssssss", $usuario_nombre, $reparacion_id, $fecha_emision, $total, $estado, $metodo_pago);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>✅ Factura generada exitosamente para el cliente: $usuario_nombre.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error al generar la factura: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-3">
        <button onclick="history.back()" class="btn btn-secondary">🔙 Volver Atrás</button>
    </div>

    <!-- Sección de Generación de Factura -->
    <div class="card p-4 mb-4">
        <h4 class="text-center">➕ Crear Nueva Factura</h4>
        <form method="POST">
            <div class="mb-3">
                <label for="usuario_id">Cliente (Nombre):</label>
                <input type="text" name="usuario_id" id="usuario_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="reparacion_id">Reparación:</label>
                <input type="text" name="reparacion_id" id="reparacion_id" class="form-control">
            </div>
            <div class="mb-3">
                <label for="total">Total (€):</label>
                <input type="number" name="total" id="total" step="0.01" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" class="form-control">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Pagado">Pagado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="metodo_pago">Método de Pago:</label>
                <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">💾 Guardar Factura</button>
        </form>
    </div>

    <!-- Resultados de la Búsqueda -->
    <div id="resultadoBusqueda">
        <?php if (!empty($facturas)): ?>
        <h3 class="text-center mt-4">📑 Facturas del Usuario</h3>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Cliente</th>
                    <th>Reparación ID</th>
                    <th>Fecha Emisión</th>
                    <th>Total (€)</th>
                    <th>Estado</th>
                    <th>Método de Pago</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $factura): ?>
                <tr>
                    <td><?= $factura['Usuario_ID'] ?></td>
                    <td><?= $factura['Reparacion_ID'] ?></td>
                    <td><?= date('d/m/Y', strtotime($factura['Fecha_Emision'])) ?></td>
                    <td><?= number_format($factura['Total'], 2) ?> €</td>
                    <td>
                        <span class="badge bg-<?= $factura['Estado'] == 'Pagado' ? 'success' : 'warning' ?>">
                            <?= $factura['Estado'] ?>
                        </span>
                    </td>
                    <td><?= $factura['Metodo_Pago'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php elseif (isset($_GET['usuario_id'])): ?>
            <div class="alert alert-warning text-center">⚠️ No se encontraron facturas para este usuario.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function limpiarBusqueda() {
    document.getElementById('usuario_id_buscar').value = ""; // Limpia el campo de búsqueda
    document.getElementById('resultadoBusqueda').innerHTML = ""; // Borra los resultados
}
</script>
