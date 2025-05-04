<?php
include 'conexion.php'; // Conexión a la base de datos
include 'header.php'; 

$mensaje = ""; // Variable para el script de alerta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si el campo 'vehiculo_nombre' está definido
    $vehiculo_nombre = isset($_POST['vehiculo_nombre']) ? $_POST['vehiculo_nombre'] : '';

    // Recibir los otros campos
    $mecanico_id = !empty($_POST['mecanico_id']) ? $_POST['mecanico_id'] : NULL;
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];
    $estado = $_POST['estado'];
    $fecha_ingreso = date('Y-m-d');
    $fecha_salida = NULL; 

    // Validación de campos
    if (empty($vehiculo_nombre) || empty($descripcion) || empty($costo) || empty($estado)) {
        $mensaje = "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '⚠️ Campos incompletos',
                    text: 'Por favor, completa todos los campos antes de continuar.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>";
    } else {
        // Preparar la consulta para insertar la reparación
        $sql = "INSERT INTO reparaciones (ID, Vehiculo_ID, Mecanico_ID, Descripcion, Costo, Estado, Fecha_Ingreso, Fecha_Salida) 
                VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdsss", $vehiculo_nombre, $mecanico_id, $descripcion, $costo, $estado, $fecha_ingreso, $fecha_salida);

        if ($stmt->execute()) {
            // Alerta de registrado correcto sin redirección
            $mensaje = "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '✅ Reparación Guardada',
                        text: 'La reparación se ha registrado correctamente.',
                        icon: 'success',
                        showConfirmButton: true
                    });
                });
            </script>";
        } else {
            // Alerta de error
            $mensaje = "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '❌ Error al guardar',
                        text: 'No se pudo registrar la reparación. Intenta de nuevo.',
                        icon: 'error',
                        confirmButtonText: 'Reintentar'
                    });
                });
            </script>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">🔧 Agregar Reparación</h2>

    <form action="agregar_reparacion.php" method="POST">
        <!-- Nombre del cliente/vehículo -->
        <div class="mb-3">
            <label for="vehiculo_nombre" class="form-label">🚗 Nombre del Cliente/Vehículo:</label>
            <input type="text" name="vehiculo_nombre" id="vehiculo_nombre" class="form-control" required>
        </div>

        <!-- Mecánico -->
        <div class="mb-3">
            <label for="mecanico_id" class="form-label">👨‍🔧 Mecánico ID (opcional):</label>
            <input type="number" name="mecanico_id" id="mecanico_id" class="form-control">
        </div>

        <!-- Descripción -->
        <div class="mb-3">
            <label for="descripcion" class="form-label">📋 Descripción:</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>

        <!-- Costo -->
        <div class="mb-3">
            <label for="costo" class="form-label">💰 Costo (€):</label>
            <input type="number" name="costo" id="costo" min="5" step="1" class="form-control" required>
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label for="estado" class="form-label">📌 Estado:</label>
            <select name="estado" id="estado" class="form-control">
                <option value="Pendiente">Pendiente</option>
                <option value="En Progreso">En Progreso</option>
                <option value="Completado">Completado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">💾 Guardar Reparación</button>
    </form>

    <?php 
        echo $mensaje; // Se ejecuta después de que se cargue la página
    ?>
</div>

<!-- Importar SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
